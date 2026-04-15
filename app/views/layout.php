<?php /** @var string $viewFile */ ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <meta name="description" content="Napfény Tours - utazási ötletek, képgaléria, kapcsolat és admin felület.">
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
    <script defer src="<?= e(url('assets/js/app.js')) ?>"></script>
</head>
<body>
<header class="site-header">
    <div class="container header-top">
        <a class="brand" href="<?= e(url('')) ?>">
            <span class="brand-logo">☀️</span>
            <span>
                <strong>Napfény Tours</strong>
                <small>nyaralási ötletek és szállásajánlatok</small>
            </span>
        </a>
        <div class="header-user">
            <?php if (is_logged_in()): ?>
                <?php $user = current_user(); ?>
                <span>Bejelentkezett: <?= e($user['last_name']) ?> <?= e($user['first_name']) ?> (<?= e($user['login_name']) ?>)</span>
                <form action="<?= e(url('kilepes')) ?>" method="post">
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                    <button class="btn btn-outline" type="submit">Kilépés</button>
                </form>
            <?php else: ?>
                <span>Vendég felhasználó</span>
            <?php endif; ?>
        </div>
    </div>

    <nav class="main-nav">
        <div class="container nav-inner">
            <a href="<?= e(url('')) ?>" class="<?= current_route() === '' || current_route() === 'fooldal' ? 'active' : '' ?>">Főoldal</a>
            <a href="<?= e(url('kepek')) ?>" class="<?= current_route() === 'kepek' ? 'active' : '' ?>">Képek</a>
            <a href="<?= e(url('kapcsolat')) ?>" class="<?= str_starts_with(current_route(), 'kapcsolat') ? 'active' : '' ?>">Kapcsolat</a>
            <?php if (is_admin()): ?>
                <a href="<?= e(url('uzenetek')) ?>" class="<?= current_route() === 'uzenetek' ? 'active' : '' ?>">Üzenetek</a>
            <?php endif; ?>
            <a href="<?= e(url('crud')) ?>" class="<?= str_starts_with(current_route(), 'crud') ? 'active' : '' ?>">CRUD</a>
            <?php if (!is_logged_in()): ?>
                <a href="<?= e(url('bejelentkezes')) ?>" class="<?= current_route() === 'bejelentkezes' ? 'active' : '' ?>">Bejelentkezés</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="container page-content">
    <?php foreach (get_flashes() as $flash): ?>
        <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endforeach; ?>

    <?php require $viewFile; ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <section>
            <h3>Napfény Tours</h3>
            <p>Utazási témájú weboldal képgalériával, kapcsolat űrlappal és admin felülettel.</p>
        </section>
        <section>
            <h3>Kapcsolat</h3>
            <p><?= e(config('app.office_address')) ?></p>
            <p><?= e(config('app.office_phone')) ?></p>
            <p><?= e(config('app.office_email')) ?></p>
        </section>
        <section>
            <h3>Admin belépés</h3>
            <p>Admin teszt belépés: <code><?= e(config('app.demo_login')) ?></code> / <code><?= e(config('app.demo_password')) ?></code></p>
        </section>
    </div>
</footer>
</body>
</html>
