<?php

namespace App\Controllers;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Offer;

class HomeController
{
    public function index(): void
    {
        $stats = [
            'helysegek' => Location::countAll(),
            'szallodak' => Hotel::countAll(),
            'ajanlatok' => Offer::countAll(),
            'legalacsonyabb_ar' => Offer::minPrice(),
        ];

        render('home', [
            'title' => 'Főoldal - Napfény Tours',
            'stats' => $stats,
            'destinations' => Location::withHotelCounts(),
            'offers' => Offer::cheapest(6),
        ]);
    }
}
