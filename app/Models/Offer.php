<?php

namespace App\Models;

class Offer
{
    public static function countAll(): int
    {
        $row = db()->fetch('SELECT COUNT(*) AS cnt FROM tavasz');
        return (int) ($row['cnt'] ?? 0);
    }

    public static function minPrice(): int
    {
        $row = db()->fetch('SELECT MIN(ar) AS min_price FROM tavasz');
        return (int) ($row['min_price'] ?? 0);
    }

    public static function cheapest(int $limit = 6): array
    {
        if (config('database.driver') === 'sqlite') {
            return db()->fetchAll(
                'SELECT t.szalloda_az, t.indulas, t.idotartam, t.ar,
                        sz.nev AS szalloda_nev, sz.besorolas, h.nev AS helyseg_nev, h.orszag
                 FROM tavasz t
                 INNER JOIN szalloda sz ON sz.az = t.szalloda_az
                 INNER JOIN helyseg h ON h.az = sz.helyseg_az
                 ORDER BY t.ar ASC, t.indulas ASC
                 LIMIT ' . (int) $limit
            );
        }

        return db()->fetchAll(
            'SELECT t.szalloda_az, t.indulas, t.idotartam, t.ar,
                    sz.nev AS szalloda_nev, sz.besorolas, h.nev AS helyseg_nev, h.orszag
             FROM tavasz t
             INNER JOIN szalloda sz ON sz.az = t.szalloda_az
             INNER JOIN helyseg h ON h.az = sz.helyseg_az
             ORDER BY t.ar ASC, t.indulas ASC
             LIMIT :limit',
            ['limit' => $limit]
        );
    }
}
