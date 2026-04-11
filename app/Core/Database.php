<?php

namespace App\Core;

use PDO;
use PDOStatement;
use RuntimeException;

class Database
{
    private ?PDO $pdo = null;
    private string $driver;
    private string $jsonPath = '';
    private array $jsonData = [];
    private string $lastInsertId = '0';

    public function __construct(array $config)
    {
        $this->driver = $config['driver'] ?? 'json';

        if ($this->driver === 'sqlite') {
            $dbPath = $config['sqlite_path'] ?? '';
            if ($dbPath === '' || !file_exists($dbPath)) {
                throw new RuntimeException('A SQLite adatbázisfájl nem található: ' . $dbPath);
            }
            $this->pdo = new PDO('sqlite:' . $dbPath);
            $this->pdo->exec('PRAGMA foreign_keys = ON');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return;
        }

        if ($this->driver === 'mysql') {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'] ?? 'localhost',
                $config['dbname'] ?? '',
                $config['charset'] ?? 'utf8mb4'
            );

            $this->pdo = new PDO(
                $dsn,
                $config['username'] ?? '',
                $config['password'] ?? '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return;
        }

        if ($this->driver === 'json') {
            $this->jsonPath = $config['json_path'] ?? '';
            if ($this->jsonPath === '') {
                throw new RuntimeException('A JSON adatbázisfájl nincs megadva.');
            }
            if (!file_exists($this->jsonPath)) {
                file_put_contents($this->jsonPath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
            $this->jsonData = json_decode((string) file_get_contents($this->jsonPath), true) ?: [];
            return;
        }

        throw new RuntimeException('Nem támogatott adatbázis-driver: ' . $this->driver);
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        if ($this->pdo === null) {
            throw new RuntimeException('A query() csak PDO-alapú driverrel használható.');
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            if (is_int($value) || ctype_digit((string) $value)) {
                $stmt->bindValue(is_string($key) ? ':' . ltrim($key, ':') : $key + 1, (int) $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(is_string($key) ? ':' . ltrim($key, ':') : $key + 1, $value);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): array|false
    {
        if ($this->driver !== 'json') {
            return $this->query($sql, $params)->fetch();
        }

        $rows = $this->jsonSelect($sql, $params);
        return $rows[0] ?? false;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        if ($this->driver !== 'json') {
            return $this->query($sql, $params)->fetchAll();
        }

        return $this->jsonSelect($sql, $params);
    }

    public function execute(string $sql, array $params = []): bool
    {
        if ($this->driver !== 'json') {
            return $this->query($sql, $params)->rowCount() >= 0;
        }

        return $this->jsonExecute($sql, $params);
    }

    public function lastInsertId(): string
    {
        if ($this->driver !== 'json') {
            return $this->pdo?->lastInsertId() ?: '0';
        }

        return $this->lastInsertId;
    }

    private function saveJson(): void
    {
        file_put_contents($this->jsonPath, json_encode($this->jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function table(string $name): array
    {
        return $this->jsonData[$name] ?? [];
    }

    private function jsonSelect(string $sql, array $params): array
    {
        $sql = trim(preg_replace('/\s+/', ' ', $sql));

        if ($sql === 'SELECT * FROM users WHERE id = :id') {
            return array_values(array_filter($this->table('users'), fn($row) => (int) $row['id'] === (int) $params['id']));
        }

        if ($sql === 'SELECT * FROM users WHERE login_name = :login') {
            return array_values(array_filter($this->table('users'), fn($row) => $row['login_name'] === $params['login']));
        }

        if ($sql === 'SELECT * FROM helyseg ORDER BY orszag, nev') {
            $rows = $this->table('helyseg');
            usort($rows, fn($a, $b) => [$a['orszag'], $a['nev']] <=> [$b['orszag'], $b['nev']]);
            return $rows;
        }

        if ($sql === 'SELECT * FROM helyseg WHERE az = :id') {
            return array_values(array_filter($this->table('helyseg'), fn($row) => (int) $row['az'] === (int) $params['id']));
        }

        if ($sql === 'SELECT COUNT(*) AS cnt FROM helyseg') {
            return [['cnt' => count($this->table('helyseg'))]];
        }

        if (str_contains($sql, 'FROM helyseg h LEFT JOIN szalloda sz')) {
            $rows = [];
            foreach ($this->table('helyseg') as $location) {
                $count = 0;
                foreach ($this->table('szalloda') as $hotel) {
                    if ((int) $hotel['helyseg_az'] === (int) $location['az']) {
                        $count++;
                    }
                }
                $rows[] = [
                    'az' => $location['az'],
                    'nev' => $location['nev'],
                    'orszag' => $location['orszag'],
                    'hotels' => $count,
                ];
            }
            usort($rows, fn($a, $b) => [$a['orszag'], $a['nev']] <=> [$b['orszag'], $b['nev']]);
            return $rows;
        }

        if ($sql === 'SELECT COUNT(*) AS cnt FROM szalloda') {
            return [['cnt' => count($this->table('szalloda'))]];
        }

        if (str_contains($sql, 'FROM szalloda sz INNER JOIN helyseg h')) {
            $locations = [];
            foreach ($this->table('helyseg') as $loc) {
                $locations[(int) $loc['az']] = $loc;
            }

            $rows = [];
            foreach ($this->table('szalloda') as $hotel) {
                $loc = $locations[(int) $hotel['helyseg_az']] ?? ['nev' => '', 'orszag' => ''];
                $rows[] = $hotel + ['helyseg_nev' => $loc['nev'], 'orszag' => $loc['orszag']];
            }
            usort($rows, fn($a, $b) => $a['nev'] <=> $b['nev']);
            return $rows;
        }

        if ($sql === 'SELECT * FROM szalloda WHERE az = :code') {
            return array_values(array_filter($this->table('szalloda'), fn($row) => $row['az'] === $params['code']));
        }

        if ($sql === 'SELECT COUNT(*) AS cnt FROM tavasz') {
            return [['cnt' => count($this->table('tavasz'))]];
        }

        if ($sql === 'SELECT MIN(ar) AS min_price FROM tavasz') {
            $prices = array_map(fn($row) => (int) $row['ar'], $this->table('tavasz'));
            return [['min_price' => $prices ? min($prices) : 0]];
        }

        if (str_contains($sql, 'FROM tavasz t INNER JOIN szalloda sz ON sz.az = t.szalloda_az')) {
            $hotels = [];
            foreach ($this->table('szalloda') as $hotel) {
                $hotels[$hotel['az']] = $hotel;
            }
            $locations = [];
            foreach ($this->table('helyseg') as $loc) {
                $locations[(int) $loc['az']] = $loc;
            }

            $rows = [];
            foreach ($this->table('tavasz') as $offer) {
                $hotel = $hotels[$offer['szalloda_az']] ?? null;
                if (!$hotel) continue;
                $loc = $locations[(int) $hotel['helyseg_az']] ?? ['nev' => '', 'orszag' => ''];
                $rows[] = [
                    'szalloda_az' => $offer['szalloda_az'],
                    'indulas' => $offer['indulas'],
                    'idotartam' => $offer['idotartam'],
                    'ar' => $offer['ar'],
                    'szalloda_nev' => $hotel['nev'],
                    'besorolas' => $hotel['besorolas'],
                    'helyseg_nev' => $loc['nev'],
                    'orszag' => $loc['orszag'],
                ];
            }
            usort($rows, fn($a, $b) => [(int) $a['ar'], $a['indulas']] <=> [(int) $b['ar'], $b['indulas']]);
            if (preg_match('/LIMIT (\d+)/', $sql, $m)) {
                return array_slice($rows, 0, (int) $m[1]);
            }
            return array_slice($rows, 0, (int) ($params['limit'] ?? 6));
        }

        if ($sql === 'SELECT * FROM messages WHERE id = :id') {
            return array_values(array_filter($this->table('messages'), fn($row) => (int) $row['id'] === (int) $params['id']));
        }

        if (str_contains($sql, 'FROM messages m LEFT JOIN users u')) {
            $users = [];
            foreach ($this->table('users') as $user) {
                $users[(int) $user['id']] = $user;
            }
            $rows = [];
            foreach ($this->table('messages') as $message) {
                if ($message['user_id'] === null || $message['user_id'] === '') {
                    $display = 'Vendég';
                } else {
                    $user = $users[(int) $message['user_id']] ?? null;
                    $display = $user
                        ? trim($user['last_name'] . ' ' . $user['first_name'] . ' (' . $user['login_name'] . ')')
                        : 'Vendég';
                }
                $rows[] = $message + ['display_sender' => $display];
            }
            usort($rows, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
            return $rows;
        }

        if (str_contains($sql, 'FROM gallery_images g LEFT JOIN users u')) {
            $users = [];
            foreach ($this->table('users') as $user) {
                $users[(int) $user['id']] = $user;
            }
            $rows = [];
            foreach ($this->table('gallery_images') as $image) {
                $user = $users[(int) $image['user_id']] ?? [];
                $rows[] = $image + [
                    'last_name' => $user['last_name'] ?? null,
                    'first_name' => $user['first_name'] ?? null,
                    'login_name' => $user['login_name'] ?? null,
                ];
            }
            usort($rows, fn($a, $b) => strcmp($b['uploaded_at'], $a['uploaded_at']));
            return $rows;
        }

        throw new RuntimeException('Nem támogatott JSON SELECT lekérdezés: ' . $sql);
    }

    private function jsonExecute(string $sql, array $params): bool
    {
        $sql = trim(preg_replace('/\s+/', ' ', $sql));

        if (str_starts_with($sql, 'INSERT INTO users')) {
            $rows = $this->table('users');
            $newId = (count($rows) ? max(array_column($rows, 'id')) : 0) + 1;
            $rows[] = [
                'id' => $newId,
                'last_name' => $params['last_name'],
                'first_name' => $params['first_name'],
                'login_name' => $params['login_name'],
                'password_hash' => $params['password_hash'],
                'created_at' => $params['created_at'],
            ];
            $this->jsonData['users'] = $rows;
            $this->lastInsertId = (string) $newId;
            $this->saveJson();
            return true;
        }

        if (str_starts_with($sql, 'INSERT INTO szalloda')) {
            $rows = $this->table('szalloda');
            $rows[] = $params;
            $this->jsonData['szalloda'] = $rows;
            $this->saveJson();
            return true;
        }

        if (str_starts_with($sql, 'UPDATE szalloda SET')) {
            $rows = $this->table('szalloda');
            foreach ($rows as &$row) {
                if ($row['az'] === $params['az']) {
                    $row = [
                        'az' => $row['az'],
                        'nev' => $params['nev'],
                        'besorolas' => $params['besorolas'],
                        'helyseg_az' => $params['helyseg_az'],
                        'tengerpart_tav' => $params['tengerpart_tav'],
                        'repter_tav' => $params['repter_tav'],
                        'felpanzio' => $params['felpanzio'],
                    ];
                    break;
                }
            }
            unset($row);
            $this->jsonData['szalloda'] = $rows;
            $this->saveJson();
            return true;
        }

        if ($sql === 'DELETE FROM szalloda WHERE az = :code') {
            $this->jsonData['szalloda'] = array_values(array_filter(
                $this->table('szalloda'),
                fn($row) => $row['az'] !== $params['code']
            ));
            $this->jsonData['tavasz'] = array_values(array_filter(
                $this->table('tavasz'),
                fn($row) => $row['szalloda_az'] !== $params['code']
            ));
            $this->saveJson();
            return true;
        }

        if (str_starts_with($sql, 'INSERT INTO messages')) {
            $rows = $this->table('messages');
            $newId = (count($rows) ? max(array_column($rows, 'id')) : 0) + 1;
            $rows[] = [
                'id' => $newId,
                'user_id' => $params['user_id'],
                'sender_name' => $params['sender_name'],
                'email' => $params['email'],
                'subject' => $params['subject'],
                'message' => $params['message'],
                'created_at' => $params['created_at'],
            ];
            $this->jsonData['messages'] = $rows;
            $this->lastInsertId = (string) $newId;
            $this->saveJson();
            return true;
        }

        if (str_starts_with($sql, 'INSERT INTO gallery_images')) {
            $rows = $this->table('gallery_images');
            $newId = (count($rows) ? max(array_column($rows, 'id')) : 0) + 1;
            $rows[] = [
                'id' => $newId,
                'user_id' => $params['user_id'],
                'title' => $params['title'],
                'filename' => $params['filename'],
                'uploaded_at' => $params['uploaded_at'],
            ];
            $this->jsonData['gallery_images'] = $rows;
            $this->lastInsertId = (string) $newId;
            $this->saveJson();
            return true;
        }

        throw new RuntimeException('Nem támogatott JSON EXECUTE lekérdezés: ' . $sql);
    }
}
