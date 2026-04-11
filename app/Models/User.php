<?php

namespace App\Models;

class User
{
    public static function find(int $id): ?array
    {
        return db()->fetch('SELECT * FROM users WHERE id = :id', ['id' => $id]) ?: null;
    }

    public static function findByLogin(string $login): ?array
    {
        return db()->fetch('SELECT * FROM users WHERE login_name = :login', ['login' => $login]) ?: null;
    }

    public static function create(array $data): void
    {
        db()->execute(
            'INSERT INTO users (last_name, first_name, login_name, password_hash, created_at)
             VALUES (:last_name, :first_name, :login_name, :password_hash, :created_at)',
            [
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'login_name' => $data['login_name'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'created_at' => now(),
            ]
        );
    }
}
