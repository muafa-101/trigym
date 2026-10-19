<?php

declare(strict_types=1);

namespace TriGym\Controllers;

use TriGym\Core\Auth;
use TriGym\Core\Request;
use TriGym\Core\Response;
use TriGym\Core\View;
use TriGym\Models\GymClass;
use TriGym\Models\GymStatus;
use TriGym\Repositories\CheckinRepository;
use TriGym\Repositories\ClassRepository;

final class DashboardController
{
    public function __construct(
        private readonly Request $request = new Request(),
        private readonly Auth $auth = new Auth(),
        private readonly ClassRepository $classes = new ClassRepository(),
        private readonly CheckinRepository $checkins = new CheckinRepository(),
    ) {
    }

    public function index(): void
    {
        $member = $this->auth->requireLogin();

        $all = $this->classes->all();
        $enrolledIds = $this->classes->enrolledIds($member->id);

        // Kelas utama = kelas pertama yang diikuti member (ditampilkan di header accordion).
        $primary = null;
        foreach ($all as $class) {
            if ($enrolledIds !== [] && $class->id === $enrolledIds[0]) {
                $primary = $class;
                break;
            }
        }
        $others = array_values(array_filter(
            $all,
            static fn (GymClass $c): bool => $primary === null || $c->id !== $primary->id
        ));

        View::render('dashboard', [
            'pageTitle'   => 'TRIGYM - Member Dashboard',
            'header'      => 'dashboard',
            'footer'      => 'app',
            'script'      => 'dashboard',
            'bodyClass'   => 'min-h-screen flex flex-col',
            'member'      => $member,
            'gymStatus'   => new GymStatus($this->checkins->countTodayAll()),
            'primary'     => $primary,
            'others'      => $others,
            'enrolledIds' => $enrolledIds,
            'stats'       => [
                'visits'   => $this->checkins->countThisMonth($member->id),
                'classes'  => count($enrolledIds),
                'daysLeft' => $member->daysLeft(),
            ],
        ]);
    }

    public function enroll(): void
    {
        $member = $this->auth->requireLogin();

        if (!$this->request->isPost() || !$this->request->csrfIsValid()) {
            Response::redirect('dashboard.php');
        }

        $classId = (int) $this->request->input('class_id', 0);
        if ($this->classes->exists($classId)) {
            $this->classes->enroll($member->id, $classId);
        }
        Response::redirect('dashboard.php#kelas');
    }

    public function generateCode(): void
    {
        $member = $this->auth->user();
        if ($member === null) {
            Response::json(['error' => 'Belum login.'], 401);
        }
        if (!$this->request->isPost() || !$this->request->csrfIsValid()) {
            Response::json(['error' => 'Token tidak valid.'], 419);
        }

        Response::json(['code' => $this->checkins->create($member->id)]);
    }
}
