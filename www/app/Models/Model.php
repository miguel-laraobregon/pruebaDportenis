<?php

namespace App\Models;

use Core\Database;
use InvalidArgumentException;
use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table; // Propiedad para almacenar el nombre de la tabla
    protected array $fillable = [];
    public ?int $id = null;

    public function __construct(Database $database = new Database())
    {
        $this->db = $database->getConnection();
    }

    /**
     * Función genérica para buscar un registro por un campo y su valor.
     *
     * @param string $field Campo por el que se desea realizar la búsqueda
     * @param mixed $value Valor a buscar
     *
     * @return array|null El resultado de la consulta o null si no se encuentra
     */
    public function findByField(string $field, $value): ?array
    {

        // Validamos que el campo esté en los campos permitidos
        if (!in_array($field, array_merge($this->fillable, ['id']))) {
            throw new InvalidArgumentException("Campo '{$field}' no permitido.");
        }

        // Construimos consulta dinámica
        $operator = is_numeric($value) ? '=' : 'LIKE';
        $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} {$operator} :value");
        $query->bindParam(':value', $value);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Consulta un registro por su id usando la función genérica findByField.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return $this->findByField('id', $id);
    }

    /**
     * Funcion generica que recibe sentencia sql para obtención de datos y realiza consultas
     *
     * @param string $sql
     *
     * @return array
     */
    public function fetchAllQuery(string $sql): array
    {
        $query = $this->db->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los registros de la tabla usando la función genérica fetchAllQuery
     *
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id";
        return $this->fetchAllQuery($sql);
    }

    /**
     * Elimina un registro por su id.
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(): bool
    {
        $query = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $result = $query->execute(['id' => $this->id]);
        if (!$result) {
            throw new \Exception("Ocurrió un error en la eliminación del registro ");
        }
        return $result;
    }

    /**
     * Funcion que manda llamar funcion correcta para guardar o actualizar un registro en la base de datos
     *
     * @return boolean
     */
    public function save(): bool
    {
        return $this->id ? $this->update() : $this->create();
    }

    /**
     * Crea un nuevo registro en la base de datos.
     *
     * @return boolean
     */
    protected function create(): bool
    {
        $fields = implode(', ', $this->fillable);
        $values = ':' . implode(', :', $this->fillable);

        $query = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$values})");

        $values = $this->setAttributes();

        $result = $query->execute($values);

        if (!$result) {
            throw new \Exception("Ocurrió un error en la creación del registro ");
        }
        $this->id = (int)$this->db->lastInsertId();

        return $result;
    }

    /**
     * Actualiza un registro existente.
     *
     * @return boolean
     */
    protected function update(): bool
    {
        $setValues = implode(', ', array_map(fn($field) => "{$field} = :{$field}", $this->fillable));

        $query = $this->db->prepare("UPDATE {$this->table} SET {$setValues} WHERE id = :id");

        $values = $this->setAttributes();
        $values['id'] = $this->id;

        $result = $query->execute($values);
        if (!$result) {
            throw new \Exception("Ocurrió un error en la actualización del registro ");
        }
        return $result;
    }

    /**
     * Define un array de atributos basados en los definidos en array fillable
     *
     * @return array
     */
    private function setAttributes(): array
    {
        $attributes = [];
        foreach ($this->fillable as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }

        return $attributes;
    }
}
