<?php

namespace App\Models;

class GalleryImage
{
    public static function all(): array
    {
        return db()->fetchAll(
            'SELECT g.*, u.last_name, u.first_name, u.login_name
             FROM gallery_images g
             LEFT JOIN users u ON u.id = g.user_id
             ORDER BY g.uploaded_at DESC'
        );
    }

    public static function create(array $data): void
    {
        db()->execute(
            'INSERT INTO gallery_images (user_id, title, filename, uploaded_at)
             VALUES (:user_id, :title, :filename, :uploaded_at)',
            [
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'filename' => $data['filename'],
                'uploaded_at' => now(),
            ]
        );
    }
}
