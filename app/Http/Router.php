<?php

namespace App\Http;

require_once 'Route.php';
require_once 'StockController.php';

class Router
{
    public array $routes;

    public function load() {

        $this->routes = [
            Route::get("/", \App\Http\StockController::class, 'lookupForm'),
            Route::post("/check-stock", \App\Http\StockController::class, 'checkStock'),
            Route::post("/process-order", \App\Http\StockController::class, 'processOrder'),
        ];

    }

    public function __construct() {
        $this->load();
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $route) {
            if ($route->httpMethod != $method) {
                continue;
            }

            if (preg_match("#^$route->url$#", $url, $matches)) {
                $params = array_slice($matches, 1);

                try {
                    echo (new $route->controller)->{$route->action}($params);
                } catch (\Exception $e) {
                    http_response_code(500);
                    echo '500 Server Error';
                }

                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo '404 Not Found';
    }
}