<?php

use App\Controllers\MenuController;


$controller = new MenuController();

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}
$path = $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas para el manejo de menús

// Ruta para mostrar todos los menús
if ($method === 'GET' && ( $path === '/menus' || $path === '/' )) {
    $controller->index();
}
// Ruta para crear un nuevo menú
elseif ($method === 'GET' && $path === '/menus/create') {
    $controller->create();
}
// Ruta para guardar un nuevo menú
elseif ($method === 'POST' && $path === '/menus') {
    $controller->store($_POST);
}
// Ruta para editar un menú por id
elseif ($method === 'GET' && preg_match('#^/menus/(\d+)/edit$#', $path, $matches)) {
    $controller->edit((int)$matches[1]);
}
// Ruta para actualizar un menú por id
elseif ($method === 'PUT' && preg_match('#^/menus/(\d+)$#', $path, $matches)) {
    parse_str(file_get_contents('php://input'), $_PUT);
    $controller->update((int)$matches[1], $_PUT);
}
// Ruta para eliminar un menú por id
elseif ($method === 'DELETE' && preg_match('#^/menus/(\d+)$#', $path, $matches)) {
    $controller->destroy((int)$matches[1]);
}
// Ruta para mostrar un menú basado en su link (por ejemplo, "/catalogo")
elseif ($method === 'GET' && preg_match('#^/([a-zA-Z0-9-]+)$#', $path, $matches)) {
    $menuName = $matches[1];  // Captura el nombre del menú
    $controller->showByName($menuName);  // Llama al método showByName en el controlador
} else {
    http_response_code(404);
    echo "404 - Not Found";
}
