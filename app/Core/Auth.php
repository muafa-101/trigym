<?php

declare(strict_types=1);

namespace TriGym\Core;

use TriGym\Models\Member;
use TriGym\Repositories\MemberRepository;

final class Auth
{
    private const SESSION_KEY = 'member_id';

    private ?Member $cached = null;

    public function __construct(private readonly MemberRepository $members = new MemberRepository())
    {
    }

    public function attempt(string $email, string $password): bool
    {
        $member = $this->members->findByEmail($email);
        if ($member === null || !password_verify($password, $member->passwordHash)) {
            return false;
        }
        $this->login($member);
        return true;
    }

    public function login(Member $member): void
    {
        Session::regenerate();
        Session::set(self::SESSION_KEY, $member->id);
        $this->cached = $member;
    }

    public function logout(): void
    {
        $this->cached = null;
        Session::destroy();
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function user(): ?Member
    {
        if ($this->cached === null) {
            $id = Session::get(self::SESSION_KEY);
            if (is_int($id)) {
                $this->cached = $this->members->findById($id);
            }
        }
        return $this->cached;
    }

    /** Wajib login; jika belum, arahkan ke halaman masuk. */
    public function requireLogin(): Member
    {
        $member = $this->user();
        if ($member === null) {
            Response::redirect('signin.php');
        }
        return $member;
    }
}
