<?php

namespace App\Components\Api;

class Request
{
    // Получить метод запроса
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // Получить заголовок запроса
    public function getAccept(): string
    {
        return $_SERVER['HTTP_ACCEPT'];
    }

    // Получить URI
    public function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        
        return rawurldecode($uri);
    }

    // Получить body
    public function getBody()
    {
        return json_decode(file_get_contents('php://input'));
    }
}