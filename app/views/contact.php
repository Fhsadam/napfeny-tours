<?php
$contactName = (string) old('name');
if ($contactName === '' && current_user()) {
    $contactName = trim(current_user()['last_name'] . ' ' . current_user()['first_name']);
}
?>
<section class="section-heading">
    <h1>Kapcsolat</h1>
    <p>Az űrlap ellenőrzi a megadott adatokat, majd elmenti az üzenetet.</p>
</section>

<section class="card">
    <form action="<?= e(url('kapcsolat')) ?>" method="post" class="stack-form" id="contact-form" novalidate>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <label>Név
            <input type="text" name="name" value="<?= e($contactName) ?>" data-rule="name">
            <small class="error-text"></small>
        </label>

        <label>E-mail cím
            <input type="email" name="email" value="<?= e((string) old('email')) ?>" data-rule="email">
            <small class="error-text"></small>
        </label>

        <label>Tárgy
            <input type="text" name="subject" value="<?= e((string) old('subject')) ?>" data-rule="subject">
            <small class="error-text"></small>
        </label>

        <label>Üzenet
            <textarea name="message" rows="7" data-rule="message"><?= e((string) old('message')) ?></textarea>
            <small class="error-text"></small>
        </label>

        <button class="btn" type="submit">Üzenet küldése</button>
    </form>
</section>
