<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\HomeController;
use App\Controller\FrontController;
use App\Controller\JsonController;
use App\Controller\OrderController;
use App\Controller\ProductController;
use App\Controller\CartController;


RouterMapper::addGetRoute('/{id}',[FrontController::class, 'home']);
RouterMapper::addGetRoute('/',[HomeController::class,'index']);

RouterMapper::addPostRoute('/order',[OrderController::class, 'create']);
RouterMapper::addGetRoute('/order',[JsonController::class, 'findByName']);
RouterMapper::addGetRoute('/order/{id}', [HomeController::class, 'findById']);
RouterMapper::addGetRoute('/order/page', [HomeController::class, 'orderPage']);

RouterMapper::addPostRoute('/product', [ProductController::class, 'createProduct']);
RouterMapper::addGetRoute('/product', [ProductController::class, 'getProducts']);
RouterMapper::addGetRoute('/product/sort', [ProductController::class, 'getSortedProducts']);

RouterMapper::addPostRoute('/cart', [CartController::class,'createCart']);
RouterMapper::addPostRoute('/cart/add', [CartController::class, 'addToCart']);
RouterMapper::addGetRoute('/cart/product',[CartController::class, 'getProductsInCart']);
RouterMapper::addDeleteRoute('/cart/product/{id}', [CartController::class, 'deleteProduct']);

RouterMapper::addGetRoute('/decorator', [HomeController::class, 'decorator']);
RouterMapper::addGetRoute('/error',[HomeController::class, 'error']);