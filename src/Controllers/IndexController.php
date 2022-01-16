<?php

namespace App\Controllers;

use App\Components\Api\Response;

class IndexController
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    public function ping()
    {
        $this->response->sendSuccessMessage('Сервер работает хорошо');
    }
}