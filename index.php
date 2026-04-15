<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/app/bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\GalleryController;
use App\Controllers\HomeController;
use App\Controllers\HotelController;
use App\Controllers\MessageController;
use App\Core\Router;

$router = new Router();

$router->get('', [HomeController::class, 'index']);
$router->get('fooldal', [HomeController::class, 'index']);

$router->get('bejelentkezes', [AuthController::class, 'show']);
$router->post('bejelentkezes', [AuthController::class, 'login']);
$router->post('regisztracio', [AuthController::class, 'register']);
$router->post('kilepes', [AuthController::class, 'logout']);
$router->get('dev-belepes', [AuthController::class, 'devLogin']);

$router->get('kepek', [GalleryController::class, 'index']);
$router->post('kepek/feltoltes', [GalleryController::class, 'store']);

$router->get('kapcsolat', [ContactController::class, 'show']);
$router->post('kapcsolat', [ContactController::class, 'store']);
$router->get('kapcsolat/sikeres', [ContactController::class, 'success']);

$router->get('uzenetek', [MessageController::class, 'index']);

$router->get('crud', [HotelController::class, 'index']);
$router->get('crud/uj', [HotelController::class, 'create']);
$router->post('crud/uj', [HotelController::class, 'store']);
$router->get('crud/szerkeszt/{code}', [HotelController::class, 'edit']);
$router->post('crud/szerkeszt/{code}', [HotelController::class, 'update']);
$router->post('crud/torles/{code}', [HotelController::class, 'delete']);

$router->dispatch();
