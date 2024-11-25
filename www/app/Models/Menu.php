<?php

namespace App\Models;

use Config\Database;
use PDO;

class Menu {
    private readonly PDO $db;

    public function __construct(Database $database = new Database()) {
        $this->db = $database->getConnection();
    }

    /**
     * getAll - Función que consulta todos los registros de menu
     *
     * @return array
     */
    public function getAll(): array {
        $query = $this->db->query(
            "SELECT m1.id, 
                    m1.name, 
                    m1.description,
                    m1.navLink,
                    IFNULL(m1.parent_id,0) as parent_id,
                    IFNULL(m2.name,'') AS parent_name
            FROM menus m1 
            LEFT JOIN menus m2
            ON m1.parent_id = m2.id
            ORDER BY m1.id"
        );
        return $query->fetchAll();
    }

    /**
     * getAllParents - Función que consulta los registros de menu que no tienen parent_id
     *
     * @return array
     */
    public function getAllParents(): array {
        $query = $this->db->query(
            "SELECT m1.id, m1.name
             FROM menus m1 
             WHERE m1.parent_id IS NULL
             ORDER BY m1.id"
        );
        return $query->fetchAll();
    }

    /**
     * findById - Función que consulta un menu por id
     *
     * @param integer $id
     *
     * @return array|null
     */
    public function findById(int $id): ?array {
        $query = $this->db->prepare("SELECT * FROM menus WHERE id = :id");
        $query->execute(['id' => $id]);
        $result = $query->fetch();
        return $result ?: null;
    }

    /**
     * create - Función que guarda el registro de un menú
     *
     * @param string $name
     * @param string $description
     * @param integer|null $parentId
     *
     * @return boolean
     */
    public function create(string $name, string $description, ?int $parentId = null): bool {
        $query = $this->db->prepare(
            "INSERT INTO menus (name, description, navLink, parent_id ) VALUES (:name, :description, :navLink, :parent_id)"
        );
        return $query->execute([
            'name' => $name,
            'description' => $description,
            'navLink' => strtolower(str_replace(' ', '-', $name)),
            'parent_id' => $parentId
        ]);
    }

    /**
     * update - Función que actualiza el registro de un menú
     *
     * @param integer $id
     * @param string $name
     * @param string $description
     * @param integer|null $parentId
     *
     * @return boolean
     */
    public function update(int $id, string $name, string $description, ?int $parentId = null): bool {
        $query = $this->db->prepare(
            "UPDATE menus 
            SET name = :name, 
            description = :description, 
            navLink = :navLink, 
            parent_id = :parent_id 
            WHERE id = :id"
        );
        return $query->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'navLink' => strtolower(str_replace(' ', '-', $name)),
            'parent_id' => $parentId
        ]);
    }

    /**
     * delete - Funcion que elimina el registro de un menú
     *
     * @param integer $id
     *
     * @return boolean
     */
    public function delete(int $id): bool {
        $query = $this->db->prepare("DELETE FROM menus WHERE id = :id");
        return $query->execute(['id' => $id]);
    }

    /**
     * findByLink - Funcion que consulta el registro de un menú por link
     *
     * @param [type] $link
     *
     * @return void
     */
    public function findByLink($link)
    {
        $query = $this->db->prepare("SELECT * FROM menus WHERE navLink LIKE :link ");
        $query->bindParam(':link', $link);
        $query->execute();
        $result = $query->fetch();
        return $result ?: null;
    }
}
