<?php

namespace App\Traits;

trait ViewTrait
{
    
    /**
     * view - Renderiza una vista.
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
     * redirect - Redirecciona a una pagina
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
}
