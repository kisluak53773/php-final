<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../App/Router/Routes.php';

use App\App;
use App\Router\Wrapper\Request;
use App\Router\Wrapper\Uri;

$uriBuffer = explode('?', $_SERVER['REQUEST_URI']);
$request = new Request();
$uri = new Uri();
$uri = $uri->withPath($uriBuffer[0]);
$request = $request->withMethod($_SERVER['REQUEST_METHOD'])->withQueryParams($_GET)->withUri($uri);

if (!empty($_POST)) {
    $request = $request->withParsedBody($_POST);
}

$app = new App($request);

$app->boot()->run();