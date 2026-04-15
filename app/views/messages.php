<section class="section-heading">
    <h1>Beérkezett üzenetek</h1>
    <p>Ezt az oldalt csak az admin látja. Az üzenetek a legfrissebbel kezdve jelennek meg.</p>
</section>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Küldés ideje</th>
                <th>Küldő</th>
                <th>E-mail</th>
                <th>Tárgy</th>
                <th>Üzenet</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= e(date('Y.m.d H:i', strtotime($message['created_at']))) ?></td>
                <td><?= e($message['display_sender']) ?></td>
                <td><?= e($message['email']) ?></td>
                <td><?= e($message['subject']) ?></td>
                <td><?= e(strlen($message['message']) > 90 ? substr($message['message'], 0, 90) . '…' : $message['message']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
