<?php

namespace App\Controllers;

use App\Models\Message;

class MessageController
{
    public function index(): void
    {
        require_admin();

        render('messages', [
            'title' => 'Admin üzenetek - Napfény Tours',
            'messages' => Message::allLatestFirst(),
        ]);
    }
}
