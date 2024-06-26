<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\HomeController;
use App\Controller\FrontController;

RouterMapper::addGetRoute('/{id}',[FrontController::class, 'home']);
RouterMapper::addGetRoute('/about',[HomeController::class, 'about']);