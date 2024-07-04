<?php

declare(strict_types=1);

namespace App\Router;

use App\Router\Exception\ControllerMethodNotDefined;
use App\Router\Exception\ControllerNotFound;
use App\Router\Exception\NotFoundException;
use App\Router\Exception\WrongControllerDefinition;
use App\App;

class RouterMapper
{
    private static array $routes = [];

    public static function register(string $routeUrl, string $method, array $action): void
    {
        self::$routes[$method][$routeUrl] = $action;
    }

    public static function addGetRoute($routeUrl, array $action): void
    {
        self::register($routeUrl, 'GET', $action);
    }

    public static function addDeleteRoute($routeUrl, array $action): void
    {
        self::register($routeUrl, 'DELETE', $action);
    }

    public static function addPostRoute($routeUrl, array $action): void
    {
        self::register($routeUrl, 'POST', $action);
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function parseUrl(string $routeUrl, string $pattern): array|false
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<${1}>\d+)', $pattern);
        $regex = str_replace('/', '\/', $regex);

        if (preg_match('/^'.$regex.'$/', $routeUrl, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return false;
    }

    /**
     * @param string $routeUrl
     * @param string $method
     *
     * @return mixed
     *
     * @throws ControllerMethodNotDefined
     * @throws ControllerNotFound
     * @throws NotFoundException
     * @throws WrongControllerDefinition
     */
    public static function handleRoute(string $routeUrl, string $method): mixed
    {
        if (isset(self::$routes[$method])) {
            $routes = self::$routes[$method];

            foreach ($routes as $storedUrl => $action) {
                $params = self::parseUrl($routeUrl, $storedUrl);

                if (is_array($params)) {
                    if (!is_array($action)) {
                        http_response_code(500);
                        echo json_encode(["message" => "Something went wrong"]);
                        throw new WrongControllerDefinition();
                    }

                    [$class, $method] = $action;

                    if (!class_exists($class)) {
                        http_response_code(500);
                        echo json_encode(["message" => "Something went wrong"]);
                        throw new ControllerNotFound();
                    }

                    $classInstance = App::container()->get($class);

                    if (!method_exists($classInstance, $method)) {
                        http_response_code(500);
                        echo json_encode(["message" => "Something went wrong"]);
                        throw new ControllerMethodNotDefined();
                    }

                    return call_user_func_array([$classInstance, $method], [...$params]);

                }
            }
        }

        http_response_code(404);
        echo json_encode(["message" => "Route not found."]);
        throw new NotFoundException();
    }
}