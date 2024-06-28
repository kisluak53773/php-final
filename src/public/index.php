<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../App/Router/Routes.php';

use App\App;

$uri = explode('?', $_SERVER['REQUEST_URI']);

$app = new App(
    [
        'uri' => $uri[0],
        'method' => $_SERVER['REQUEST_METHOD']
    ]
);

$app->boot()->run();
