<?php

namespace App\Controllers;

use App\Models\GalleryImage;

class GalleryController
{
    public function index(): void
    {
        render('gallery', [
            'title' => 'Képek - Napfény Tours',
            'galleryImages' => GalleryImage::all(),
        ]);
    }

    public function store(): void
    {
        require_auth();
        verify_csrf();

        $title = trim($_POST['title'] ?? '');
        $file = $_FILES['image'] ?? null;

        if ($title === '') {
            flash('error', 'Adj meg egy képcímet is.');
            redirect('kepek');
        }

        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flash('error', 'Válassz ki egy feltöltendő képet.');
            redirect('kepek');
        }

        if (($file['size'] ?? 0) > 3 * 1024 * 1024) {
            flash('error', 'A kép legfeljebb 3 MB lehet.');
            redirect('kepek');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (!isset($allowed[$mime])) {
            flash('error', 'Csak JPG, PNG, WEBP vagy GIF képet lehet feltölteni.');
            redirect('kepek');
        }

        $filename = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
        $target = base_path('uploads/gallery/' . $filename);

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            flash('error', 'A kép mentése nem sikerült.');
            redirect('kepek');
        }

        GalleryImage::create([
            'title' => $title,
            'filename' => $filename,
            'user_id' => (int) current_user()['id'],
        ]);

        flash('success', 'A kép sikeresen feltöltve.');
        redirect('kepek');
    }
}
