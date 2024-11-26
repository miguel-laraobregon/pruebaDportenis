<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno desde .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require __DIR__ . '/../router.php';
