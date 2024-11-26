<?php

namespace App\Controllers;

use App\Interfaces\CrudController;
use App\Models\Menu;
use App\Traits\ViewTrait;

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
        $menu = new Menu();
        $menu->name = $data['name'];
        $menu->description = $data['description'];
        $menu->navLink = strtolower(str_replace(' ', '-', $data['name']));
        $menu->parent_id = $data['parent_id'] ? (int)$data['parent_id'] : null;
        $menu->save();
        $this->redirect('/menus');
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
        $menu = $this->menu->findById($id);
        $menus = $this->menu->getAllParents();
        $this->view('menu/edit', compact('menu', 'menus'));
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
        $menu = new Menu();
        $menu->id = $id;
        $menu->name = $data['name'];
        $menu->description = $data['description'];
        $menu->navLink = strtolower(str_replace(' ', '-', $data['name']));
        $menu->parent_id = $data['parent_id'] ? (int)$data['parent_id'] : null;
        $menu->save();
        $this->redirect('/menus');
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
        $this->menu->deleteById($id);
        $this->redirect('/menus');
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
        // Buscar el menú por su link
        $menu = $this->menu->findByField('navLink', $link);

        if(!$menu){
            http_response_code(404);
            echo "Menu not found";
        }
        
        $menus = $this->menu->getAll();
        $menuNavBar = $this->getMenusNavbar($menus);
        $this->view('menu/show', compact('menu', 'menuNavBar'));
    }

    /**
     * getMenusNavBar - Función que procesa y devuelve menu con formato para navBar
     *
     * @param array $menus
     *
     */
    public function getMenusNavBar(array $menus = []): array 
    {
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
