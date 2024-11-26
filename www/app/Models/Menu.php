<?php

namespace App\Models;

class Menu extends Model
{
    protected string $table = 'menus';

    protected array $fillable = [
        'name',
        'description',
        'navLink',
        'parent_id',
    ];

    public ?string $name = null;
    public ?string $description = null;
    public ?string $navLink = null;
    public ?int $parent_id = null;

    /**
     * Función que consulta todos los registros de menu
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT m1.id, 
                    m1.name, 
                    m1.description,
                    m1.navLink,
                    IFNULL(m1.parent_id,0) as parent_id,
                    IFNULL(m2.name,'') AS parent_name
            FROM menus m1 
            LEFT JOIN menus m2
            ON m1.parent_id = m2.id
            ORDER BY m1.id";

        return $this->fetchAllQuery($sql);
    }

    /**
     * Función que consulta los registros de menu que no tienen parent_id
     *
     * @return array
     */
    public function getAllParents(): array
    {
        $sql = "SELECT m1.id, m1.name
            FROM menus m1 
            WHERE m1.parent_id IS NULL
            ORDER BY m1.id";
        return $this->fetchAllQuery($sql);
    }


    /**
     * Funcion que consulta los submenus de un menu
     *
     * @return array
     */
    public function getSubsByParent(): array
    {
        $sql = "SELECT * FROM menus WHERE parent_id = {$this->id} ";
        return $this->fetchAllQuery($sql);
    }
}
