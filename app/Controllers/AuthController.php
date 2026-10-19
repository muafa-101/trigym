<?php

declare(strict_types=1);

namespace TriGym\Controllers;

use TriGym\Core\Auth;
use TriGym\Core\Request;
use TriGym\Core\Response;
use TriGym\Core\Session;
use TriGym\Core\Validator;
use TriGym\Core\View;
use TriGym\Repositories\MemberRepository;
use TriGym\Repositories\PackageRepository;

final class AuthController
{
    public function __construct(
        private readonly Request $request = new Request(),
        private readonly Auth $auth = new Auth(),
        private readonly MemberRepository $members = new MemberRepository(),
        private readonly PackageRepository $packages = new PackageRepository(),
    ) {
    }

    /** Entry point signup.php: GET = tampilkan form, POST = proses. */
    public function signup(): void
    {
        $this->redirectIfLoggedIn();
        $this->request->isPost() ? $this->handleSignup() : $this->showSignup();
    }

    /** Entry point signin.php. */
    public function signin(): void
    {
        $this->redirectIfLoggedIn();
        $this->request->isPost() ? $this->handleSignin() : $this->showSignin();
    }

    public function logout(): void
    {
        if ($this->request->isPost() && $this->request->csrfIsValid()) {
            $this->auth->logout();
        }
        Response::redirect('index.php');
    }

    private function showSignup(): void
    {
        View::render('signup', [
            'pageTitle' => 'Sign Up - TRIGYM',
            'header'    => 'auth',
            'footer'    => 'legal',
            'bodyClass' => 'flex min-h-screen flex-col',
            'errors'    => Session::pullFlash('errors', []),
            'old'       => Session::pullFlash('old', []),
            'packageId' => (int) $this->request->query('paket', 0),
        ]);
    }

    private function handleSignup(): void
    {
        if (!$this->request->csrfIsValid()) {
            Response::abort(419, 'Sesi berakhir, silakan muat ulang halaman.');
        }

        $data = [
            'fullname' => $this->request->string('fullname'),
            'email'    => $this->request->string('email'),
            'password' => (string) $this->request->input('password', ''),
            'terms'    => $this->request->input('terms'),
        ];

        $v = (new Validator($data))
            ->required('fullname', 'Nama lengkap')
            ->required('email', 'Email')->email('email')
            ->required('password', 'Password')->minLength('password', 8, 'Password')
            ->accepted('terms', 'Anda harus menyetujui Syarat & Ketentuan.');

        if ($v->passes() && $this->members->emailExists($data['email'])) {
            $v->addError('email', 'Email sudah terdaftar.');
        }

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            Session::flash('old', ['fullname' => $data['fullname'], 'email' => $data['email']]);
            Response::redirect('signup.php' . $this->packageQuery());
        }

        $package = $this->packages->find((int) $this->request->input('package_id', 0))
            ?? $this->packages->first();

        $member = $this->members->create($data['fullname'], $data['email'], $data['password'], $package);
        $this->auth->login($member);
        Response::redirect('dashboard.php');
    }

    private function showSignin(): void
    {
        View::render('signin', [
            'pageTitle' => 'Sign In - TRIGYM',
            'header'    => 'auth',
            'footer'    => 'legal',
            'bodyClass' => 'flex min-h-screen flex-col',
            'errors'    => Session::pullFlash('errors', []),
            'old'       => Session::pullFlash('old', []),
        ]);
    }

    private function handleSignin(): void
    {
        if (!$this->request->csrfIsValid()) {
            Response::abort(419, 'Sesi berakhir, silakan muat ulang halaman.');
        }

        $email = $this->request->string('email');
        $password = (string) $this->request->input('password', '');

        if ($email === '' || $password === '' || !$this->auth->attempt($email, $password)) {
            Session::flash('errors', ['form' => 'Email atau password salah.']);
            Session::flash('old', ['email' => $email]);
            Response::redirect('signin.php');
        }

        Response::redirect('dashboard.php');
    }

    private function redirectIfLoggedIn(): void
    {
        if ($this->auth->check()) {
            Response::redirect('dashboard.php');
        }
    }

    private function packageQuery(): string
    {
        $id = (int) $this->request->input('package_id', 0);
        return $id > 0 ? '?paket=' . $id : '';
    }
}
