<?php
declare(strict_types=1);

namespace App\Middleware;

use function App\Helpers\current_user;
use function App\Helpers\flash;
use function App\Helpers\is_admin;
use function App\Helpers\redirect;

final class AuthMiddleware
{
    public static function requireAuth(): void
    {
        if (current_user() === null) {
            flash('warning', 'Please sign in to continue.');
            redirect('/auth/login');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (!is_admin()) {
            flash('danger', 'Admin access is required.');
            redirect('/');
        }
    }
}
