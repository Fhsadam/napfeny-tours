<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return User::find((int) $_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $login, string $password): bool
    {
        $user = User::findByLogin($login);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['user_id'] = (int) $user['id'];
        session_regenerate_id(true);
        return true;
    }

    public static function loginById(int $userId): void
    {
        $_SESSION['user_id'] = $userId;
        session_regenerate_id(true);
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        $adminLogins = config('app.admin_logins', []);
        return in_array($user['login_name'] ?? '', $adminLogins, true);
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }
}
