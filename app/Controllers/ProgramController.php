<?php

declare(strict_types=1);

namespace TriGym\Controllers;

use TriGym\Core\Auth;
use TriGym\Core\Request;
use TriGym\Core\Response;
use TriGym\Core\View;
use TriGym\Repositories\ProgramRepository;

/**
 * ProgramController – menangani CRUD Program PresidentKu.
 *
 * CREATE  POST programs.php?action=store
 * READ    GET  programs.php
 * UPDATE  POST programs.php?action=update&id=X
 * DELETE  POST programs.php?action=delete&id=X
 */
final class ProgramController
{
    public function __construct(
        private readonly Request           $request   = new Request(),
        private readonly Auth              $auth      = new Auth(),
        private readonly ProgramRepository $programs  = new ProgramRepository(),
    ) {
    }

    /** READ – tampilkan daftar program + form tambah/edit. */
    public function index(): void
    {
        $member = $this->auth->requireLogin();

        $editId  = (int) $this->request->query('edit', 0);
        $editItem = $editId > 0 ? $this->programs->find($editId) : null;

        View::render('programs', [
            'pageTitle' => 'TRIGYM - Program Management',
            'header'    => 'dashboard',
            'footer'    => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member'    => $member,
            'programs'  => $this->programs->all(),
            'editItem'  => $editItem,
        ]);
    }

    /** CREATE – simpan program baru. */
    public function store(): void
    {
        $this->auth->requireLogin();
        $this->validateCsrfPost();

        $nama     = $this->request->string('nama');
        $deskripsi = $this->request->string('deskripsi');

        if ($nama !== '' && $deskripsi !== '') {
            $this->programs->create(['nama' => $nama, 'deskripsi' => $deskripsi]);
        }

        Response::redirect('programs.php');
    }

    /** UPDATE – perbarui program yang sudah ada. */
    public function update(): void
    {
        $this->auth->requireLogin();
        $this->validateCsrfPost();

        $id       = (int) $this->request->query('id', 0);
        $nama     = $this->request->string('nama');
        $deskripsi = $this->request->string('deskripsi');

        if ($id > 0 && $nama !== '' && $deskripsi !== '') {
            $this->programs->update($id, ['nama' => $nama, 'deskripsi' => $deskripsi]);
        }

        Response::redirect('programs.php');
    }

    /** DELETE – hapus program. */
    public function destroy(): void
    {
        $this->auth->requireLogin();
        $this->validateCsrfPost();

        $id = (int) $this->request->query('id', 0);
        if ($id > 0) {
            $this->programs->delete($id);
        }

        Response::redirect('programs.php');
    }

    private function validateCsrfPost(): void
    {
        if (!$this->request->isPost() || !$this->request->csrfIsValid()) {
            Response::redirect('programs.php');
        }
    }
}
