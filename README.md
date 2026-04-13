# Napfény Tours – Web-programozás 1 beadandó

Ez a mintaalkalmazás a megadott **Utazás – 3 táblás** adatbázist használja fel, és a 7a front-controller mintára épülő továbbfejlesztett beadandó-megoldás.

## Fő funkciók

- Reszponzív, vízszintes menüs felület
- Főoldal videóval, YouTube beágyazással és Google térképpel
- Regisztráció, belépés, kilépés
- Képgaléria és képfeltöltés bejelentkezett felhasználóknak
- Kapcsolat űrlap kliens- és szerveroldali validációval
- Üzenetek listázása fordított időrendben
- CRUD felület a `szalloda` táblához

## Belépési adatok

- Login: `demo`
- Jelszó: `Demo12345!`

## Helyi futtatás

```bash
php -S 127.0.0.1:8000
```

Alapértelmezésben a projekt **JSON driverrel** fut, mert egyes helyi környezetekben nincs telepítve PDO MySQL/SQLite driver.

## Internetes tárhely / MySQL

A tárhelyes ellenőrzéshez állítsd át a `config/database.php` vagy a környezeti változók alapján a következőket:

- `DB_DRIVER=mysql`
- `DB_HOST=localhost`
- `DB_NAME=adatb`
- `DB_USER=adatbf`
- `DB_PASS=...`

Ezután futtasd a `database/import_mysql.sql` állományt phpMyAdminból vagy Adminerből.

## Megjegyzés

A dokumentáció sablonját és a képernyőképeket a `docs` mappában találod.
