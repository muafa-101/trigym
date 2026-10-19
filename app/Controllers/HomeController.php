<?php

declare(strict_types=1);

namespace TriGym\Controllers;

use TriGym\Core\Auth;
use TriGym\Core\View;
use TriGym\Repositories\ClassRepository;
use TriGym\Repositories\FacilityRepository;
use TriGym\Repositories\PackageRepository;

final class HomeController
{
    public function __construct(
        private readonly Auth $auth = new Auth(),
        private readonly FacilityRepository $facilities = new FacilityRepository(),
        private readonly ClassRepository $classes = new ClassRepository(),
        private readonly PackageRepository $packages = new PackageRepository(),
    ) {
    }

    public function index(): void
    {
        View::render('home', [
            'pageTitle'  => 'TRIGYM - Tugas Pemrograman Web',
            'header'     => 'home',
            'footer'     => 'legal',
            'script'     => 'home',
            'user'       => $this->auth->user(),
            'facilities' => $this->facilities->all(),
            'classes'    => $this->classes->all(),
            'packages'   => $this->packages->all(),
        ]);
    }
}
