<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">Felfedezés • tengerpart • élmény</p>
        <h1>Utazási ajánlatok napfényes úti célokhoz</h1>
        <p class="lead">Ezen az oldalon a legnépszerűbb helyszínek, szállodák és ajánlatok láthatók egy helyen. A főoldalon videók és térkép is segíti a tájékozódást.</p>
        <div class="hero-actions">
            <a class="btn" href="<?= e(url('crud')) ?>">Szállodák</a>
            <a class="btn btn-outline" href="<?= e(url('kepek')) ?>">Képgaléria</a>
        </div>
    </div>
    <div class="hero-card">
        <h2>Gyors statisztika</h2>
        <ul class="stats-list">
            <li><strong><?= e((string) $stats['helysegek']) ?></strong><span>helyszín</span></li>
            <li><strong><?= e((string) $stats['szallodak']) ?></strong><span>szálloda</span></li>
            <li><strong><?= e((string) $stats['ajanlatok']) ?></strong><span>tavaszi ajánlat</span></li>
            <li><strong><?= number_format((int) $stats['legalacsonyabb_ar'], 0, ',', ' ') ?> Ft</strong><span>legkedvezőbb ár</span></li>
        </ul>
    </div>
</section>

<section class="card-grid">
    <?php foreach (array_slice($destinations, 0, 6) as $destination): ?>
        <article class="card">
            <h3><?= e($destination['nev']) ?></h3>
            <p><strong>Ország:</strong> <?= e($destination['orszag']) ?></p>
            <p><strong>Szállodák száma:</strong> <?= e((string) $destination['hotels']) ?></p>
        </article>
    <?php endforeach; ?>
</section>

<section class="section">
    <div class="section-heading">
        <h2>Kiemelt ajánlatok</h2>
        <p>Néhány kedvező árú ajánlat a jelenlegi kínálatból.</p>
    </div>
    <div class="offer-grid">
        <?php foreach ($offers as $offer): ?>
            <article class="offer-card">
                <h3><?= e($offer['szalloda_nev']) ?></h3>
                <p><?= e($offer['helyseg_nev']) ?>, <?= e($offer['orszag']) ?></p>
                <p><?= str_repeat('★', (int) $offer['besorolas']) ?></p>
                <p>Indulás: <strong><?= e(date('Y.m.d', strtotime($offer['indulas']))) ?></strong></p>
                <p>Időtartam: <strong><?= e((string) $offer['idotartam']) ?> nap</strong></p>
                <p class="price"><?= number_format((int) $offer['ar'], 0, ',', ' ') ?> Ft</p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="media-grid">
    <article class="media-card">
        <h2>Saját videó</h2>
        <video controls preload="metadata" width="100%">
            <source src="<?= e(url('assets/video/promo.mp4')) ?>" type="video/mp4">
            A böngésződ nem támogatja a videólejátszást.
        </video>
        <p>Rövid, 5 másodperces helyi videó a beadandó követelményhez.</p>
    </article>
    <article class="media-card">
        <h2>YouTube videó</h2>
        <div class="iframe-wrap">
            <iframe src="https://www.youtube.com/embed/Scxs7L0vhZ4?si=3XtV7g1cVw7tRZwa" title="Utazási inspiráció" loading="lazy" allowfullscreen></iframe>
        </div>
        <p>Külső szolgáltatótól beágyazott videó.</p>
    </article>
</section>

<section class="section map-section">
    <div class="section-heading">
        <h2>Irodánk helye</h2>
        <p>A budapesti iroda helye Google térképen.</p>
    </div>
    <div class="iframe-wrap map-wrap">
        <iframe
            src="https://www.google.com/maps?q=1134%20Budapest%2C%20V%C3%A1ci%20%C3%BAt%201-3&z=15&output=embed"
            title="Napfény Tours térkép"
            loading="lazy"
            allowfullscreen></iframe>
    </div>
</section>
