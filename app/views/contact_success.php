<section class="section-heading">
    <h1>Az üzenetet sikeresen elmentettük</h1>
    <p>Az alábbi adatok kerültek eltárolásra és külön oldalon is megjelennek.</p>
</section>

<article class="card message-preview">
    <h2><?= e($messageData['subject']) ?></h2>
    <p><strong>Küldő:</strong> <?= e($messageData['sender_name']) ?></p>
    <p><strong>E-mail:</strong> <?= e($messageData['email']) ?></p>
    <p><strong>Küldés ideje:</strong> <?= e(date('Y.m.d H:i:s', strtotime($messageData['created_at']))) ?></p>
    <p><strong>Üzenet:</strong></p>
    <p><?= nl2br(e($messageData['message'])) ?></p>
</article>
