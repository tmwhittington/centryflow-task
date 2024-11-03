<?php

namespace App\Http;

class Route
{

    public $url;

    public $httpMethod;
    public $controller;

    public $action;


    public function __construct($url, $method, $controller, $action = null) {
        $this->url = $url;
        $this->httpMethod = $method;
        $this->controller = $controller;
        $this->action = $action ?? '__invoke';
    }

    public static function get($url, $controller, $action = null) {
        return new Route($url, "GET", $controller, $action);
    }

    public static function post($url, $controller, $action = null) {
        return new Route($url, "POST", $controller, $action);
    }


}