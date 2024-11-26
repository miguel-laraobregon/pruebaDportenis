<?php

namespace Core;

class Router
{
    private array $routes = [];

    /**
     * Agrega una nueva ruta al enrutador.
     *
     * @param string $method Método HTTP de la ruta (GET, POST, PUT, DELETE)
     * @param string $path Ruta definida
     * @param array $action Acción asociada, especificada como [Controlador::class, 'metodo']
     *
     * @return void
     */
    public function add(string $method, string $path, array $action): void
    {
        $this->routes[] = compact('method', 'path', 'action');
    }

    /**
     * Despacha la solicitud HTTP a la acción correspondiente
     *
     * @param string $method Método HTTP de la solicitud
     * @param string $path Ruta solicitada por el cliente
     *
     * @return void
     */
    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($this->match($route['method'], $route['path'], $method, $path, $params)) {
                [$controllerClass, $actionMethod] = $route['action'];
                $controller = new $controllerClass();

                $bodyData = [];
                if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
                    parse_str(file_get_contents('php://input'), $bodyData);
                }

                // Llama al método del controlador con los parámetros y los datos del cuerpo
                call_user_func_array([$controller, $actionMethod], [...$params, $bodyData]);
                return;
            }
        }

        // Si no se encuentra ninguna ruta
        http_response_code(404);
        echo "404 - Not Found";
    }

    /**
     * Verifica si una solicitud coincide con una ruta registrada
     *
     * @param string $routeMethod Método HTTP de la ruta registrada
     * @param string $routePath Ruta registrada
     * @param string $method Método HTTP de la solicitud
     * @param string $path Ruta solicitada
     * @param array|null $params Parámetros extraídos de la ruta
     *
     * @return bool Retorna true si la ruta coincide, false en caso contrario
     */
    private function match(string $routeMethod, string $routePath, string $method, string $path, array|null &$params): bool
    {
        if ($routeMethod !== $method) {
            return false;
        }

        // Divide la ruta registrada y la ruta solicitada en partes
        $routeParts = explode('/', trim($routePath, '/'));
        $pathParts = explode('/', trim($path, '/'));

        // Comprueba si ambas rutas tienen la misma cantidad de segmentos
        if (count($routeParts) !== count($pathParts)) {
            return false;
        }

        $params = [];
        foreach ($routeParts as $index => $part) {
            if (preg_match('/^{(.+)}$/', $part, $matches)) {
                // Extrae el valor dinámico
                $params[] = $pathParts[$index];
            } elseif ($part !== $pathParts[$index]) {
                return false;
            }
        }

        return true;
    }
}
