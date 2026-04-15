<?php

use App\Core\Auth;
use App\Core\Database;

function config(string $path = null, mixed $default = null): mixed
{
    $config = $GLOBALS['config'] ?? [];
    if ($path === null) {
        return $config;
    }

    $segments = explode('.', $path);
    $value = $config;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function base_path(string $path = ''): string
{
    $base = dirname(__DIR__, 2);
    return rtrim($base, DIRECTORY_SEPARATOR) . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
}

function db(): Database
{
    static $database = null;
    if ($database === null) {
        $database = new Database(config('database'));
    }
    return $database;
}

function app_base_url(): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if ($scriptDir === '/' || $scriptDir === '.') {
        return '';
    }
    return rtrim($scriptDir, '/');
}

function url(string $path = ''): string
{
    $base = app_base_url();
    $path = ltrim($path, '/');
    if ($path === '') {
        return $base . '/';
    }
    return $base . '/' . $path;
}

function current_route(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = app_base_url();
    if ($base !== '' && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }
    $route = trim($uri, '/');
    return $route === 'index.php' ? '' : $route;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function render(string $view, array $data = []): void
{
    extract($data);
    $viewFile = base_path('app/views/' . $view . '.php');
    require base_path('app/views/layout.php');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Érvénytelen űrlapkérés.');
    }
}

function current_user(): ?array
{
    return Auth::user();
}

function is_logged_in(): bool
{
    return Auth::check();
}

function is_admin(): bool
{
    return Auth::isAdmin();
}

function require_auth(): void
{
    if (!is_logged_in()) {
        flash('error', 'Ehhez a művelethez előbb jelentkezz be.');
        redirect('bejelentkezes');
    }
}

function require_admin(): void
{
    if (!is_logged_in()) {
        flash('error', 'Ehhez a művelethez előbb jelentkezz be.');
        redirect('bejelentkezes');
    }

    if (!is_admin()) {
        flash('error', 'A CRUD felület csak admin jogosultsággal érhető el.');
        redirect('');
    }
}

function old_input(array $values): void
{
    $_SESSION['old'] = array_merge($_SESSION['old'] ?? [], $values);
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['old'][$key] ?? $default;
}

function clear_old_input(): void
{
    unset($_SESSION['old']);
}

function now(): string
{
    return date('Y-m-d H:i:s');
}
