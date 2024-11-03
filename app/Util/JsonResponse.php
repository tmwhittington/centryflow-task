<?php

namespace App\Util;

class JsonResponse
{

    private int $statusCode;
    private array $response;

    public function __construct(array $response, int $statusCode = 200) {
        $this->response = $response;
        $this->statusCode = $statusCode;
    }

    public function send(): string {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        return json_encode($this->response);
    }
}