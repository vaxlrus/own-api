<?php

namespace App\Components\Api;

class Response
{
    const SUCCESS = 200;
    const CREATED = 201;
    const ERROR = 404;
    const INTERNAL_ERROR = 500;

    // Отправка ответа
    private function sendData($input): string
    {
        if (is_string($input))
        {
            echo json_encode(['message' => $input]);
        }
        else
        {
            echo json_encode($input);
        }

        exit();
    }

    // Отправка успешного создания объекта
    public function sendCreated($input): void
    {
        http_response_code(self::CREATED);
        $this->sendData($input);
    }

    // Отправка успешного ответа
    public function sendSuccess($input): void
    {
        http_response_code(self::SUCCESS);
        $this->sendData($input);
    }

    // Отправка успешного сообщения
    public function sendSuccessMessage(string $message): void
    {
        http_response_code(self::SUCCESS);
        $this->sendData($message);
    }

    // Отправка ответа с ошибкой
    public function sendError(string $message): void
    {
        http_response_code(self::ERROR);
        $this->sendData($message);
    }

    // Отправка ответа с внутренней ошибкой
    public function sendInternalError(string $message): void
    {
        http_response_code(self::INTERNAL_ERROR);
        $this->sendData($message);
    }
}