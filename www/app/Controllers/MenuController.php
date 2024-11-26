<?php

namespace App\Controllers;

use App\Interfaces\CrudController;
use App\Models\Menu;
use App\Traits\ViewTrait;
use Exception;

class MenuController implements CrudController 
{
    use ViewTrait;

    private Menu $menu;
    
    public function __construct()
    {
        $this->menu = new Menu();
    }

    /**
     * index - funcion que consulta todos los menus para mostrarlos en vista principañ
     *
     * @return void
     */
    public function index(): void
    {
        $menus = $this->menu->getAll();
        $menuNavBar = $this->getMenusNavbar($menus);
        $this->view('menu/index', compact('menus', 'menuNavBar'));
    }

    /**
     * create  - Funcion que devuelve formulario de alta de menu
     *
     * @return void
     */
    public function create(): void
    {
        $menus = $this->menu->getAllParents();
        $this->view('menu/create', compact('menus'));
    }

    /**
     * store - Funcion que procesa guardado de menu
     *
     * @return void
     */
    public function store(array $data): void
    {
        try {
            $data = $this->validateMenuData($data);

            $menu = new Menu();
            $menu->name = $data['name'];
            $menu->description = $data['description'];
            $menu->navLink = strtolower(str_replace(' ', '-', $data['name']));
            $menu->parent_id = $data['parent_id'] ? (int)$data['parent_id'] : null;
            $menu->save();
            $this->redirect('/menus');
        } catch (\Throwable $e) {
            $this->handleErrorView($e, 'menu/create', [
                'menus' => $this->menu->getAllParents(),
                'data' =>  $data
            ]);
        }
    }

    /**
     * edit - Funcion que devuelve formulario de edición
     *
     * @param integer $id
     *
     * @return void
     */
    public function edit(int $id): void
    {
        try {
            $menu = $this->menu->findById($id);
            if(!$menu){
                throw new Exception("Menú no encontrado");
            }
            $menus = $this->menu->getAllParents();
            $this->view('menu/edit', compact('menu', 'menus'));
        } catch (\Throwable $e) {
            $this->handleErrorView($e);
        }
    }

    /**
     * update - funcion que actualiza menu editado
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function update(int $id, array $data): void
    {
        try {
            $data = $this->validateMenuData($data);

            $menu = new Menu();
            $menu->id = $id;
            $menu->name = $data['name'];
            $menu->description = $data['description'];
            $menu->navLink = strtolower(str_replace(' ', '-', $data['name']));
            $menu->parent_id = $data['parent_id'] ? (int)$data['parent_id'] : null;

            if($menu->parent_id === $menu->id) {
                throw new Exception("No es posible guardar como menú padre al mismo menú");
            }

            $menu->save();
            $this->redirect('/menus');
        } catch (\Throwable $e) {
            $this->handleErrorView($e, 'menu/edit', [
                'menu' => $this->menu->findById($id),
                'menus' => $this->menu->getAllParents(),
                'data' =>  $data
            ]);
        }
    }

    /**
     * destroy - Función que realiza eliminacion de registro de menú
     *
     * @param integer $id
     *
     * @return void
     */
    public function destroy(int $id): void
    {
        try {
            $menu = new Menu();
            $menu->id = $id;
    
            if(!empty($menu->getSubsByParent())) {
                throw new Exception("No es posible eliminar un menú con submenús");
            }
    
            $menu->deleteById();
            $this->redirect('/menus');
        } catch (\Throwable $e) {
            $menus = $this->menu->getAll();
            $menuNavBar = $this->getMenusNavbar($menus);
            $this->handleErrorView($e, 'menu/index', [
                'menus' => $menus,
                'menuNavBar' => $menuNavBar
            ]);
        }
    }

    /**
     * showByName - función que devuelve vista con informacion del menu (al dar click)
     *
     * @param [type] $link
     *
     * @return void
     */
    public function showByName(string $link): void
    {
        try {
            // Buscar el menú por su link
            $menu = $this->menu->findByField('navLink', $link);
            if(!$menu){
                throw new Exception("Menú no encontrado");
            }
            
            $menus = $this->menu->getAll();
            $menuNavBar = $this->getMenusNavbar($menus);
            $this->view('menu/show', compact('menu', 'menuNavBar'));

        } catch (\Throwable $e) {
            $this->handleErrorView($e);
        }
        
    }

    /**
     * getMenusNavBar - Función que procesa y devuelve menu con formato para navBar
     *
     * @param array $menus
     *
     */
    public function getMenusNavBar(array $menus = []): array 
    {
        if(empty($menus)) return [];

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

    /**
     * Función que valida que se reciba nombre y descripción 
     *
     * @param array $data
     *
     * @return void
     */
    private function validateMenuData(array $data): array {
        if (empty(trim($data['name'])) || empty(trim($data['description']))) {
            throw new Exception("El nombre y la descripción son obligatorios.");
        }
        $data['name'] = trim($data['name']);
        $data['description'] = trim($data['description']);
        return $data;
    }
}
