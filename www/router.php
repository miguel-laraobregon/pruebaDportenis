<?php

use Core\Router;
use App\Controllers\MenuController;

$router = new Router();

// Definición de rutas
$router->add('GET', '/', [MenuController::class, 'index']);
$router->add('GET', '/menus', [MenuController::class, 'index']);
$router->add('GET', '/menus/create', [MenuController::class, 'create']);
$router->add('POST', '/menus', [MenuController::class, 'store']);
$router->add('GET', '/menus/{id}/edit', [MenuController::class, 'edit']);
$router->add('PUT', '/menus/{id}', [MenuController::class, 'update']);
$router->add('DELETE', '/menus/{id}', [MenuController::class, 'destroy']);
$router->add('GET', '/{menuName}', [MenuController::class, 'showByName']);


$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

$router->dispatch($method, $path);
