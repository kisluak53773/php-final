<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\HomeController;
use App\Controller\FrontController;
use App\Controller\UserController;
use App\Controller\OrderController;


RouterMapper::addGetRoute('/{id}',[FrontController::class, 'home']);
RouterMapper::addGetRoute('/',[HomeController::class,'index']);

RouterMapper::addPostRoute('/user', [UserController::class, 'create']);

RouterMapper::addGetRoute('/test', [HomeController::class, 'test']);

RouterMapper::addPostRoute('/cookie', [HomeController::class, 'cookie']);
RouterMapper::addPostRoute('/session', [HomeController::class, 'session']);
RouterMapper::addPostRoute('/cookie/delete', [HomeController::class, 'deleteCookies']);
RouterMapper::addPostRoute('/session/destroy', [HomeController::class, 'destroySession']);

RouterMapper::addPostRoute('/order',[OrderController::class, 'create']);

RouterMapper::addGetRoute('/about',[HomeController::class, 'about']);