<?php

use App\App;

const DS = DIRECTORY_SEPARATOR;
define('PATH_ROOT', dirname(__DIR__) . DS);

require_once PATH_ROOT . 'vendor/autoload.php';

// Permet de charger le fichier .env
\Dotenv\Dotenv::createImmutable(PATH_ROOT)->load();

// Pour récupérer les informations du .env on utilise $_ENV['KEY']
define('STRIPE_PK', $_ENV['STRIPE_PK']);
define('STRIPE_SK', $_ENV['STRIPE_SK']);

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);


App::getApp()->start();