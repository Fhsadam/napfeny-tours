<section class="two-column">
    <article class="card">
        <h1>Bejelentkezés</h1>
        <form method="post" action="<?= e(url('bejelentkezes')) ?>" class="stack-form">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <label>Login név
                <input type="text" name="login" value="<?= e((string) old('login')) ?>" autocomplete="username">
            </label>
            <label>Jelszó
                <input type="password" name="password" autocomplete="current-password">
            </label>
            <button class="btn" type="submit">Belépés</button>
        </form>

        <?php if (config('app.env') === 'local'): ?>
            <p class="muted">Gyors demó: <a href="<?= e(url('dev-belepes?token=napfeny-demo')) ?>">automatikus belépés</a></p>
        <?php endif; ?>
    </article>

    <article class="card">
        <h2>Regisztráció</h2>
        <form method="post" action="<?= e(url('regisztracio')) ?>" class="stack-form">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <label>Családi név
                <input type="text" name="last_name" value="<?= e((string) old('last_name')) ?>">
            </label>
            <label>Utónév
                <input type="text" name="first_name" value="<?= e((string) old('first_name')) ?>">
            </label>
            <label>Login név
                <input type="text" name="login_name" value="<?= e((string) old('login_name')) ?>">
            </label>
            <label>Jelszó
                <input type="password" name="reg_password">
            </label>
            <label>Jelszó újra
                <input type="password" name="reg_password_confirm">
            </label>
            <button class="btn" type="submit">Regisztráció</button>
        </form>
        <p class="muted">Regisztráció után a felhasználót nem léptetjük be automatikusan.</p>
    </article>
</section>
