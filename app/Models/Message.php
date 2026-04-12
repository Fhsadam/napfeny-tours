<?php

namespace App\Models;

class Message
{
    public static function create(array $data): int
    {
        db()->execute(
            'INSERT INTO messages (user_id, sender_name, email, subject, message, created_at)
             VALUES (:user_id, :sender_name, :email, :subject, :message, :created_at)',
            [
                'user_id' => $data['user_id'],
                'sender_name' => $data['sender_name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'created_at' => now(),
            ]
        );

        return (int) db()->lastInsertId();
    }

    public static function find(int $id): ?array
    {
        return db()->fetch('SELECT * FROM messages WHERE id = :id', ['id' => $id]) ?: null;
    }

    public static function allLatestFirst(): array
    {
        $displayExpr = config('database.driver') === 'sqlite'
            ? 'u.last_name || " " || u.first_name || " (" || u.login_name || ")"'
            : 'CONCAT(u.last_name, " ", u.first_name, " (", u.login_name, ")")';

        return db()->fetchAll(
            'SELECT m.*,
                    CASE
                        WHEN m.user_id IS NULL THEN "Vendég"
                        ELSE ' . $displayExpr . '
                    END AS display_sender
             FROM messages m
             LEFT JOIN users u ON u.id = m.user_id
             ORDER BY m.created_at DESC'
        );
    }
}
