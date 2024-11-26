<?php

namespace App\Traits;

trait ViewTrait
{
    /**
     * Renderiza una vista.
     *
     * @param string $view Path de la vista.
     * @param array $data Datos a pasar a la vista.
     *
     * @return void
     */
    public function view(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            http_response_code(404);
            echo "Error: La vista '{$view}' no fue encontrada.";
        }
    }


    /**
     * Redirecciona a una pagina
     *
     * @param string $url
     *
     * @return void
     */
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Funcion que guarda error en archivo de logs y renderiza vista con errores
     *
     * @param \Throwable $e
     * @param string $view
     * @param array $data
     *
     * @return void
     */
    public function handleErrorView(\Throwable $e, string $view = 'errors/404', array $data = [])
    {
        error_log(
            "[" . date('Y-m-d H:i:s') . "] [ERROR] {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}\n",
            3,
            __DIR__ . '/../../php_errors.log'
        );
        $data = array_merge($data, ['error' => $e->getMessage()]);
        http_response_code(404);
        $this->view($view, $data);
    }
}
