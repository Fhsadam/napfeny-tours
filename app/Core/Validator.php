<?php

namespace App\Core;

class Validator
{
    public static function required(?string $value): bool
    {
        return trim((string) $value) !== '';
    }

    public static function min(?string $value, int $length): bool
    {
        return strlen(trim((string) $value)) >= $length;
    }

    public static function max(?string $value, int $length): bool
    {
        return strlen(trim((string) $value)) <= $length;
    }

    public static function email(?string $value): bool
    {
        return (bool) filter_var(trim((string) $value), FILTER_VALIDATE_EMAIL);
    }

    public static function in(mixed $value, array $allowed): bool
    {
        return in_array($value, $allowed, true);
    }
}
