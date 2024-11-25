<?php

namespace App\Controllers;

use App\Interfaces\CrudController;
use App\Models\Menu;

class MenuController implements CrudController {
    public function __construct(private readonly Menu $menuModel = new Menu()) {}

    /**
     * index - funcion que consulta todos los menus para mostrarlos en vista principañ
     *
     * @return void
     */
    public function index(): void {
        $menus = $this->menuModel->getAll();
        $menuNavBar = $this->getMenusNavbar($menus);
        require __DIR__ . '/../Views/menu/index.php';
    }

    /**
     * create  - Funcion que devuelve formulario de alta de menu
     *
     * @return void
     */
    public function create(): void {
        $menus = $this->menuModel->getAllParents();
        require __DIR__ . '/../Views/menu/create.php';
    }

    /**
     * store - Funcion que procesa guardado de menu
     *
     * @return void
     */
    public function store(array $data): void {
        $name = $data['name'];
        $description = $data['description'];
        $parentId = $data['parent_id'] ? (int)$data['parent_id'] : null;

        $this->menuModel->create($name, $description, $parentId);
        header('Location: /menus');
    }

    /**
     * edit - Funcion que devuelve formulario de edición
     *
     * @param integer $id
     *
     * @return void
     */
    public function edit(int $id): void {
        $menu = $this->menuModel->findById($id);
        $menus = $this->menuModel->getAllParents();
        require __DIR__ . '/../Views/menu/edit.php';
    }

    /**
     * update - funcion que actualiza menu editado
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function update(int $id, array $data): void {
        $name = $data['name'];
        $description = $data['description'];
        $parentId = $data['parent_id'] ? (int)$data['parent_id'] : null;

        $this->menuModel->update($id, $name, $description, $parentId);
        header('Location: /menus');
    }

    /**
     * destroy - Función que realiza eliminacion de registro de menú
     *
     * @param integer $id
     *
     * @return void
     */
    public function destroy(int $id): void {
        $this->menuModel->delete($id);
        header('Location: /menus');
    }

    /**
     * showByName - función que devuelve vista con informacion del menu (al dar click)
     *
     * @param [type] $link
     *
     * @return void
     */
    public function showByName($link)
    {
        // Buscar el menú por su link
        $menu = $this->menuModel->findByLink($link); 

        if ($menu) {
            $menus = $this->menuModel->getAll();
            $menuNavBar = $this->getMenusNavbar($menus);
            $content = $menu['description'];
            require __DIR__ . '/../Views/menu/show.php';
        } else {
            http_response_code(404);
            echo "Menu not found";
        }
    }

    /**
     * getMenusNavBar - Función que procesa y devuelve menu con formato para navBar
     *
     * @param array $menus
     *
     */
    public function getMenusNavBar($menus = []){
        if(empty($menus)) return false;

        $menuNavBar_aux = array_map(function($m){
            $menu = (Object) [
                'id' => $m['id'],
                'name' => $m['name'],
                'navLink' => $m['navLink'],
                'parent_id' => $m['parent_id'],
            ];
            return $menu;
        }, $menus);

        // Obtenemos solo los parents
        $menuNavBar = array_filter($menuNavBar_aux, function($m){
            return empty($m->parent_id);
        });

        // Obtenemos los submenus
        foreach($menuNavBar as $k => $v){
            $submenu = array_filter($menuNavBar_aux, function($m) use($v){
                return $m->parent_id == $v->id;
            });
            $v->submenu = $submenu ?? [];
        }

        return $menuNavBar;
    }
}
