<?php

declare(strict_types=1);

use TriGym\Core\Session;
use TriGym\Core\View;

/** Escape output HTML. */
function e(mixed $value): string
{
    return View::e($value);
}

/** Input hidden berisi token CSRF untuk form POST. */
function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Session::csrfToken()) . '">';
}
