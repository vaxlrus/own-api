<?php

namespace App\Exceptions;

use \Throwable;

class ApiException extends \RuntimeException
{
    public function __construct(Throwable $error)
    {
        parent::__construct("Ошибка API: " . $error->getMessage(), 0, $error);
    }
}