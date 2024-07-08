<?php

declare(strict_types=1);

namespace App\Router;

use App\Router\Exception\ControllerMethodNotDefined;
use App\Router\Exception\ControllerNotFound;
use App\Router\Exception\NotFoundException;
use App\Router\Exception\WrongControllerDefinition;
use App\App;
use App\Middleware\Contracts\MiddlewareInterface;
use App\Router\Wrapper\Request;
use App\Router\Wrapper\Response;

class RouterMapper
{
    private static array $routes = [];

    public static function register(string $routeUrl, string $method, array $action, ?array $middleware = null): void
    {
        self::$routes[$method][$routeUrl] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function addGetRoute($routeUrl, array $action, ?array $middleware = null): void
    {
        self::register($routeUrl, 'GET', $action, $middleware);
    }

    public static function addDeleteRoute($routeUrl, array $action, ?array $middleware = null): void
    {
        self::register($routeUrl, 'DELETE', $action, $middleware);
    }

    public static function addPostRoute($routeUrl, array $action, ?array $middleware = null): void
    {
        self::register($routeUrl, 'POST', $action, $middleware);
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
     * @param Request $request
     *
     * @return mixed
     *
     * @throws ControllerMethodNotDefined
     * @throws ControllerNotFound
     * @throws NotFoundException
     * @throws WrongControllerDefinition
     */
    public static function handleRoute(Request $request): void
    {
        if (isset(self::$routes[$request->getMethod()])) {
            $routes = self::$routes[$request->getMethod()];

            foreach ($routes as $storedUrl => $action) {
                $params = self::parseUrl($request->getUri()->getPath(), $storedUrl);

                if (is_array($params)) {
                    if (!is_array($action['action'])) {
                        http_response_code(500);
                        echo json_encode(["message" => "Something went wrong"]);
                        throw new WrongControllerDefinition();
                    }

                    if (isset($action['middleware']) && is_array($action['middleware'])) {
                        $middlewares =  $action['middleware'];

                        foreach ($middlewares as $middleware) {
                            if($middleware instanceof MiddlewareInterface) {
                                $middleware->process(App::request());
                            }
                        }
                    }

                    [$class, $method] = $action['action'];

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

                    $response = call_user_func_array([$classInstance, $method], [...$params]);

                    if ($response instanceof Response) {
                        echo $response->getParsedBody();
                    }

                    return;

                }
            }
        }

        http_response_code(404);
        echo json_encode(["message" => "Route not found."]);
        throw new NotFoundException();
    }
}