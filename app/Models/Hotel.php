<?php

namespace App\Models;

class Hotel
{
    public static function countAll(): int
    {
        $row = db()->fetch('SELECT COUNT(*) AS cnt FROM szalloda');
        return (int) ($row['cnt'] ?? 0);
    }

    public static function allWithLocation(): array
    {
        return db()->fetchAll(
            'SELECT sz.*, h.nev AS helyseg_nev, h.orszag
             FROM szalloda sz
             INNER JOIN helyseg h ON h.az = sz.helyseg_az
             ORDER BY sz.nev'
        );
    }

    public static function find(string $code): ?array
    {
        return db()->fetch('SELECT * FROM szalloda WHERE az = :code', ['code' => $code]) ?: null;
    }

    public static function create(array $data): void
    {
        db()->execute(
            'INSERT INTO szalloda (az, nev, besorolas, helyseg_az, tengerpart_tav, repter_tav, felpanzio)
             VALUES (:az, :nev, :besorolas, :helyseg_az, :tengerpart_tav, :repter_tav, :felpanzio)',
            $data
        );
    }

    public static function update(string $code, array $data): void
    {
        db()->execute(
            'UPDATE szalloda
             SET nev = :nev,
                 besorolas = :besorolas,
                 helyseg_az = :helyseg_az,
                 tengerpart_tav = :tengerpart_tav,
                 repter_tav = :repter_tav,
                 felpanzio = :felpanzio
             WHERE az = :az',
            [
                'az' => $code,
                'nev' => $data['nev'],
                'besorolas' => $data['besorolas'],
                'helyseg_az' => $data['helyseg_az'],
                'tengerpart_tav' => $data['tengerpart_tav'],
                'repter_tav' => $data['repter_tav'],
                'felpanzio' => $data['felpanzio'],
            ]
        );
    }

    public static function delete(string $code): void
    {
        db()->execute('DELETE FROM szalloda WHERE az = :code', ['code' => $code]);
    }
}
