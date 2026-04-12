<?php

namespace App\Models;

class Location
{
    public static function all(): array
    {
        return db()->fetchAll('SELECT * FROM helyseg ORDER BY orszag, nev');
    }

    public static function find(int $id): ?array
    {
        return db()->fetch('SELECT * FROM helyseg WHERE az = :id', ['id' => $id]) ?: null;
    }

    public static function countAll(): int
    {
        $row = db()->fetch('SELECT COUNT(*) AS cnt FROM helyseg');
        return (int) ($row['cnt'] ?? 0);
    }

    public static function withHotelCounts(): array
    {
        return db()->fetchAll(
            'SELECT h.az, h.nev, h.orszag, COUNT(sz.az) AS hotels
             FROM helyseg h
             LEFT JOIN szalloda sz ON sz.helyseg_az = h.az
             GROUP BY h.az, h.nev, h.orszag
             ORDER BY h.orszag, h.nev'
        );
    }
}
