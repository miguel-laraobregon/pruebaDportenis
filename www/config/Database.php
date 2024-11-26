<?php

namespace Config;

use PDO;
use PDOException;

class Database {

    private PDO $connection;
    private string $host = 'mysql-db';
    private string $port = '3306'; // Puerto MySQL
    private string $dbName = 'dportenis';
    private string $username = 'root';
    private string $password = 'test';

    public function __construct()
    {
        try {
            $this->connection = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4", $this->username, $this->password );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
