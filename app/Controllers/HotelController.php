<?php

namespace App\Controllers;

use App\Core\Validator;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\Message;
use App\Models\Offer;

class HotelController
{
    public function index(): void
    {
        require_admin();

        render('hotels/index', [
            'title' => 'CRUD - Szállodák',
            'hotels' => Hotel::allWithLocation(),
            'locations' => Location::withHotelCounts(),
            'offers' => Offer::cheapest(8),
            'recentMessages' => array_slice(Message::allLatestFirst(), 0, 8),
        ]);
    }

    public function create(): void
    {
        require_admin();

        render('hotels/form', [
            'title' => 'Új szálloda felvétele',
            'hotel' => null,
            'locations' => Location::all(),
            'action' => url('crud/uj'),
            'submitLabel' => 'Mentés',
        ]);
    }

    public function store(): void
    {
        require_admin();

        verify_csrf();
        $data = $this->collectData();
        old_input($data);
        $errors = $this->validate($data);

        if (Hotel::find($data['az'])) {
            $errors[] = 'Ez a szálloda azonosító már létezik.';
        }

        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('crud/uj');
        }

        Hotel::create($data);
        clear_old_input();
        flash('success', 'Az új szálloda rögzítése sikeres.');
        redirect('crud');
    }

    public function edit(string $code): void
    {
        require_admin();

        $hotel = Hotel::find($code);
        if (!$hotel) {
            flash('error', 'A keresett szálloda nem található.');
            redirect('crud');
        }

        render('hotels/form', [
            'title' => 'Szálloda szerkesztése',
            'hotel' => $hotel,
            'locations' => Location::all(),
            'action' => url('crud/szerkeszt/' . rawurlencode($code)),
            'submitLabel' => 'Módosítás mentése',
        ]);
    }

    public function update(string $code): void
    {
        require_admin();

        verify_csrf();
        $hotel = Hotel::find($code);
        if (!$hotel) {
            flash('error', 'A keresett szálloda nem található.');
            redirect('crud');
        }

        $data = $this->collectData();
        $data['az'] = $code;
        old_input($data);
        $errors = $this->validate($data, false);

        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('crud/szerkeszt/' . rawurlencode($code));
        }

        Hotel::update($code, $data);
        clear_old_input();
        flash('success', 'A szálloda adatai frissültek.');
        redirect('crud');
    }

    public function delete(string $code): void
    {
        require_admin();

        verify_csrf();

        if (!Hotel::find($code)) {
            flash('error', 'A törölni kívánt szálloda nem található.');
            redirect('crud');
        }

        Hotel::delete($code);
        flash('success', 'A szálloda törlése sikeres volt.');
        redirect('crud');
    }

    private function collectData(): array
    {
        return [
            'az' => strtoupper(trim($_POST['az'] ?? '')),
            'nev' => trim($_POST['nev'] ?? ''),
            'besorolas' => (int) ($_POST['besorolas'] ?? 0),
            'helyseg_az' => (int) ($_POST['helyseg_az'] ?? 0),
            'tengerpart_tav' => (int) ($_POST['tengerpart_tav'] ?? -1),
            'repter_tav' => (int) ($_POST['repter_tav'] ?? -1),
            'felpanzio' => (int) ($_POST['felpanzio'] ?? 0),
        ];
    }

    private function validate(array $data, bool $checkCode = true): array
    {
        $errors = [];

        if ($checkCode && !preg_match('/^[A-Z0-9]{2,4}$/', $data['az'])) {
            $errors[] = 'Az azonosító 2-4 nagybetűből vagy számból álljon.';
        }
        if (!Validator::min($data['nev'], 3)) {
            $errors[] = 'A szálloda neve legalább 3 karakter legyen.';
        }
        if (!Validator::in($data['besorolas'], [1, 2, 3, 4, 5])) {
            $errors[] = 'A besorolás 1 és 5 közötti érték lehet.';
        }
        if (!Location::find($data['helyseg_az'])) {
            $errors[] = 'Válassz ki egy létező helyszínt.';
        }
        if ($data['tengerpart_tav'] < 0 || $data['repter_tav'] < 0) {
            $errors[] = 'A távolságok nem lehetnek negatívak.';
        }
        if (!Validator::in($data['felpanzio'], [0, 1])) {
            $errors[] = 'A félpanzió értéke csak igen vagy nem lehet.';
        }

        return $errors;
    }
}
