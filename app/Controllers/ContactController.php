<?php

namespace App\Controllers;

use App\Core\Validator;
use App\Models\Message;

class ContactController
{
    public function show(): void
    {
        render('contact', ['title' => 'Kapcsolat - Napfény Tours']);
    }

    public function store(): void
    {
        verify_csrf();

        $user = current_user();
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        old_input($data);

        $errors = [];
        if (!Validator::min($data['name'], 3)) {
            $errors[] = 'A név legalább 3 karakter legyen.';
        }
        if (!Validator::email($data['email'])) {
            $errors[] = 'Adj meg egy érvényes e-mail címet.';
        }
        if (!Validator::min($data['subject'], 5)) {
            $errors[] = 'A tárgy legalább 5 karakter legyen.';
        }
        if (!Validator::min($data['message'], 10)) {
            $errors[] = 'Az üzenet legalább 10 karakter legyen.';
        }
        if (!Validator::max($data['message'], 2000)) {
            $errors[] = 'Az üzenet legfeljebb 2000 karakter lehet.';
        }

        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('kapcsolat');
        }

        $id = Message::create([
            'user_id' => $user['id'] ?? null,
            'sender_name' => $user ? trim($user['last_name'] . ' ' . $user['first_name']) : $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        clear_old_input();
        flash('success', 'Az üzenetet elmentettük az adatbázisba.');
        redirect('kapcsolat/sikeres?id=' . $id);
    }

    public function success(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $message = Message::find($id);
        if (!$message) {
            flash('error', 'A mentett üzenet nem található.');
            redirect('kapcsolat');
        }

        render('contact_success', [
            'title' => 'Üzenet elküldve - Napfény Tours',
            'messageData' => $message,
        ]);
    }
}
