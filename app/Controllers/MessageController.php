<?php

namespace App\Controllers;

use App\Models\Message;

class MessageController
{
    public function index(): void
    {
        require_auth();

        render('messages', [
            'title' => 'Üzenetek - Napfény Tours',
            'messages' => Message::allLatestFirst(),
        ]);
    }
}
