<section class="section-heading">
    <h1>Képgaléria</h1>
    <p>A feltöltés csak bejelentkezett felhasználó számára engedélyezett.</p>
</section>

<?php if (is_logged_in()): ?>
    <section class="card upload-card">
        <h2>Új kép feltöltése</h2>
        <form action="<?= e(url('kepek/feltoltes')) ?>" method="post" enctype="multipart/form-data" class="stack-form">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <label>Képcím
                <input type="text" name="title" required>
            </label>
            <label>Képfájl
                <input type="file" name="image" accept="image/*" required>
            </label>
            <button class="btn" type="submit">Feltöltés</button>
        </form>
    </section>
<?php else: ?>
    <div class="flash flash-info">A képfeltöltéshez előbb jelentkezz be.</div>
<?php endif; ?>

<section class="gallery-grid">
    <?php foreach ($galleryImages as $image): ?>
        <figure class="gallery-item">
            <img src="<?= e(url('uploads/gallery/' . $image['filename'])) ?>" alt="<?= e($image['title']) ?>" loading="lazy">
            <figcaption>
                <strong><?= e($image['title']) ?></strong><br>
                <span>Feltöltve: <?= e(date('Y.m.d H:i', strtotime($image['uploaded_at']))) ?></span><br>
                <span>Küldte: <?= e(trim(($image['last_name'] ?? '') . ' ' . ($image['first_name'] ?? ''))) ?><?= !empty($image['login_name']) ? ' (' . e($image['login_name']) . ')' : '' ?></span>
            </figcaption>
        </figure>
    <?php endforeach; ?>
</section>
