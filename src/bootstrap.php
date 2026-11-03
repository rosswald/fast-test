<?php

declare(strict_types=1);

use Core\App;
use Core\Router;

session_start();

$app = new App();
$router = new Router($app);
$router->route();