<section class="section-heading row-between">
    <div>
        <h1>Szállodák</h1>
        <p>Itt láthatók a szállodák adatai. Vendégként csak megtekinthetők, admin belépéssel szerkeszthetők is.</p>
    </div>
    <?php if (is_admin()): ?>
        <a class="btn" href="<?= e(url('crud/uj')) ?>">Új szálloda</a>
    <?php endif; ?>
</section>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Az.</th>
                <th>Név</th>
                <th>Besorolás</th>
                <th>Helység</th>
                <th>Ország</th>
                <th>Tengerpart</th>
                <th>Reptér</th>
                <th>Félpanzió</th>
                <?php if (is_admin()): ?>
                    <th>Műveletek</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($hotels as $hotel): ?>
            <tr>
                <td><?= e($hotel['az']) ?></td>
                <td><?= e($hotel['nev']) ?></td>
                <td><?= e((string) $hotel['besorolas']) ?>★</td>
                <td><?= e($hotel['helyseg_nev']) ?></td>
                <td><?= e($hotel['orszag']) ?></td>
                <td><?= e((string) $hotel['tengerpart_tav']) ?> m</td>
                <td><?= e((string) $hotel['repter_tav']) ?> km</td>
                <td><?= (int) $hotel['felpanzio'] === 1 ? 'Igen' : 'Nem' ?></td>
                <?php if (is_admin()): ?>
                    <td class="actions">
                        <a class="btn btn-sm btn-outline" href="<?= e(url('crud/szerkeszt/' . $hotel['az'])) ?>">Szerkesztés</a>
                        <form action="<?= e(url('crud/torles/' . $hotel['az'])) ?>" method="post" onsubmit="return confirm('Biztosan törlöd ezt a szállodát?');">
                            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Törlés</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (is_admin()): ?>
<section class="section">
    <div class="section-heading">
        <h2>Legutóbbi üzenetek</h2>
        <p>Az itt megjelenő üzenetek csak olvashatók. Teljes listában az Üzenetek menüpont alatt láthatók.</p>
    </div>
    <div class="table-wrap">
        <table class="data-table admin-mini-table">
            <thead>
                <tr>
                    <th>Küldés ideje</th>
                    <th>Küldő</th>
                    <th>Tárgy</th>
                    <th>Üzenet</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recentMessages as $message): ?>
                <tr>
                    <td><?= e(date('Y.m.d H:i', strtotime($message['created_at']))) ?></td>
                    <td><?= e($message['display_sender']) ?></td>
                    <td><?= e($message['subject']) ?></td>
                    <td><?= e(strlen($message['message']) > 90 ? substr($message['message'], 0, 90) . '…' : $message['message']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>
