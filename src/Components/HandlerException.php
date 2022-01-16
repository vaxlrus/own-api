<?php

// namespace App\Components;

use App\Components\Api\Response;
use App\Exceptions\NotFoundException;

function myErrorHandler(Throwable $exception)
{
    if ($exception instanceOf NotFoundException)
    {
        http_response_code(404);
        echo json_encode([
            "message" => $exception->getMessage()
        ]);

        return true;
    }

    http_response_code(500);
    echo json_encode([
        "message" => "Серверная ошибка"
    ]);

    return true;
}