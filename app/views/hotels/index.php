<section class="section-heading row-between">
    <div>
        <h1>CRUD - Szállodák</h1>
        <p>Ezen az oldalon lehet kezelni a szállodák adatait.</p>
    </div>
    <a class="btn" href="<?= e(url('crud/uj')) ?>">Új szálloda</a>
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
                <th>Műveletek</th>
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
                <td class="actions">
                    <a class="btn btn-sm btn-outline" href="<?= e(url('crud/szerkeszt/' . $hotel['az'])) ?>">Szerkesztés</a>
                    <form action="<?= e(url('crud/torles/' . $hotel['az'])) ?>" method="post" onsubmit="return confirm('Biztosan törlöd ezt a szállodát?');">
                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                        <button class="btn btn-sm btn-danger" type="submit">Törlés</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<section class="section">
    <div class="section-heading">
        <h2>Adatbázis áttekintés</h2>
        <p>Itt láthatók az adatbázis fontosabb adatai olvasási módban. Ezek tájékoztató listák, innen nem lehet szerkeszteni.</p>
    </div>

    <div class="two-column admin-panels">
        <div>
            <h3>Helységek</h3>
            <div class="table-wrap">
                <table class="data-table admin-mini-table">
                    <thead>
                        <tr>
                            <th>Az.</th>
                            <th>Ország</th>
                            <th>Helység</th>
                            <th>Szállodák</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($locations as $location): ?>
                        <tr>
                            <td><?= e((string) $location['az']) ?></td>
                            <td><?= e($location['orszag']) ?></td>
                            <td><?= e($location['nev']) ?></td>
                            <td><?= e((string) $location['hotels']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3>Kedvező ajánlatok</h3>
            <div class="table-wrap">
                <table class="data-table admin-mini-table">
                    <thead>
                        <tr>
                            <th>Szálloda</th>
                            <th>Helység</th>
                            <th>Indulás</th>
                            <th>Ár</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($offers as $offer): ?>
                        <tr>
                            <td><?= e($offer['szalloda_nev']) ?></td>
                            <td><?= e($offer['helyseg_nev']) ?></td>
                            <td><?= e(date('Y.m.d', strtotime($offer['indulas']))) ?></td>
                            <td><?= e(number_format((int) $offer['ar'], 0, ',', ' ')) ?> Ft</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

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
