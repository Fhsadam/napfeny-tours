<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Validator;
use App\Models\User;

class AuthController
{
    public function show(): void
    {
        if (Auth::check()) {
            flash('info', 'Már be vagy jelentkezve.');
            redirect('');
        }

        render('auth', ['title' => 'Belépés és regisztráció']);
    }

    public function login(): void
    {
        verify_csrf();

        $login = trim($_POST['login'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        old_input(['login' => $login]);

        if (!Validator::required($login) || !Validator::required($password)) {
            flash('error', 'A bejelentkezéshez add meg a felhasználónevet és a jelszót is.');
            redirect('bejelentkezes');
        }

        if (!Auth::attempt($login, $password)) {
            flash('error', 'Hibás bejelentkezési adatok.');
            redirect('bejelentkezes');
        }

        clear_old_input();
        flash('success', 'Sikeres bejelentkezés.');
        redirect('');
    }

    public function register(): void
    {
        verify_csrf();

        $data = [
            'last_name' => trim($_POST['last_name'] ?? ''),
            'first_name' => trim($_POST['first_name'] ?? ''),
            'login_name' => trim($_POST['login_name'] ?? ''),
            'password' => (string) ($_POST['reg_password'] ?? ''),
            'password_confirm' => (string) ($_POST['reg_password_confirm'] ?? ''),
        ];

        old_input($data);

        $errors = [];
        if (!Validator::min($data['last_name'], 2)) {
            $errors[] = 'A családi név legalább 2 karakter legyen.';
        }
        if (!Validator::min($data['first_name'], 2)) {
            $errors[] = 'Az utónév legalább 2 karakter legyen.';
        }
        if (!preg_match('/^[a-zA-Z0-9_.-]{3,30}$/', $data['login_name'])) {
            $errors[] = 'A login név 3-30 karakter hosszú lehet, és csak betűt, számot, pontot, kötőjelet vagy aláhúzást tartalmazhat.';
        }
        if (strlen($data['password']) < 8) {
            $errors[] = 'A jelszó legalább 8 karakter hosszú legyen.';
        }
        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'A két jelszó nem egyezik meg.';
        }
        if (User::findByLogin($data['login_name'])) {
            $errors[] = 'Ez a login név már foglalt.';
        }

        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('bejelentkezes');
        }

        User::create($data);
        clear_old_input();
        flash('success', 'Sikeres regisztráció. A felhasználót nem léptettük be automatikusan, most bejelentkezhetsz.');
        redirect('bejelentkezes');
    }

    public function logout(): void
    {
        verify_csrf();
        Auth::logout();
        flash('success', 'Sikeres kilépés.');
        redirect('');
    }

    public function devLogin(): void
    {
        if (config('app.env') !== 'local') {
            http_response_code(404);
            render('404', ['title' => '404']);
            return;
        }

        if (($_GET['token'] ?? '') !== 'napfeny-demo') {
            http_response_code(403);
            echo 'Tiltott';
            return;
        }

        Auth::loginById(1);
        redirect($_GET['redirect'] ?? '');
    }
}
