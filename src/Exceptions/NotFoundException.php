<?php

namespace App\Exceptions;

use \Exception;
use \Throwable;

class NotFoundException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}