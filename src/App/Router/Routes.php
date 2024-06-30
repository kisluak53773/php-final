<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\HomeController;
use App\Controller\FrontController;
use App\Controller\JsonController;
use App\Controller\OrderController;


RouterMapper::addGetRoute('/{id}',[FrontController::class, 'home']);
RouterMapper::addGetRoute('/',[HomeController::class,'index']);

RouterMapper::addPostRoute('/order',[OrderController::class, 'create']);
RouterMapper::addGetRoute('/order',[JsonController::class, 'findByName']);
RouterMapper::addGetRoute('/order/{id}', [HomeController::class, 'findById']);

RouterMapper::addGetRoute('/error',[HomeController::class, 'error']);

RouterMapper::addGetRoute('/about',[HomeController::class, 'about']);