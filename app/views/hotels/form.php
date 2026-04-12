<?php
$editing = $hotel !== null;
$current = $hotel ?? [
    'az' => old('az'),
    'nev' => old('nev'),
    'besorolas' => old('besorolas'),
    'helyseg_az' => old('helyseg_az'),
    'tengerpart_tav' => old('tengerpart_tav'),
    'repter_tav' => old('repter_tav'),
    'felpanzio' => old('felpanzio', 0),
];
?>
<section class="section-heading">
    <h1><?= e($title) ?></h1>
    <p><?= $editing ? 'A kiválasztott szálloda adatainak módosítása.' : 'Új rekord felvétele a szálloda táblába.' ?></p>
</section>

<section class="card">
    <form action="<?= e($action) ?>" method="post" class="stack-form grid-form">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <label>Azonosító
            <input type="text" name="az" value="<?= e((string) $current['az']) ?>" <?= $editing ? 'disabled' : '' ?>>
        </label>

        <label>Szálloda neve
            <input type="text" name="nev" value="<?= e((string) $current['nev']) ?>">
        </label>

        <label>Besorolás
            <select name="besorolas">
                <option value="">Válassz</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>" <?= (string) $current['besorolas'] === (string) $i ? 'selected' : '' ?>><?= $i ?>★</option>
                <?php endfor; ?>
            </select>
        </label>

        <label>Helység
            <select name="helyseg_az">
                <option value="">Válassz helyet</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= e((string) $location['az']) ?>" <?= (string) $current['helyseg_az'] === (string) $location['az'] ? 'selected' : '' ?>>
                        <?= e($location['orszag'] . ' - ' . $location['nev']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Tengerpart távolsága (m)
            <input type="number" name="tengerpart_tav" min="0" value="<?= e((string) $current['tengerpart_tav']) ?>">
        </label>

        <label>Reptér távolsága (km)
            <input type="number" name="repter_tav" min="0" value="<?= e((string) $current['repter_tav']) ?>">
        </label>

        <label>Félpanzió
            <select name="felpanzio">
                <option value="1" <?= (string) $current['felpanzio'] === '1' ? 'selected' : '' ?>>Igen</option>
                <option value="0" <?= (string) $current['felpanzio'] === '0' ? 'selected' : '' ?>>Nem</option>
            </select>
        </label>

        <div class="form-actions">
            <button class="btn" type="submit"><?= e($submitLabel) ?></button>
            <a class="btn btn-outline" href="<?= e(url('crud')) ?>">Vissza</a>
        </div>
    </form>
</section>
