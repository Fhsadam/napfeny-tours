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
