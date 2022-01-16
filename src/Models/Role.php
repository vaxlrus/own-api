<?php

namespace App\Models;

use DomainException;

class Role
{
    public string $id;
    public string $name;

    public function __construct(string $id, string $name)
    {
        if (strlen($name) === 0)
        {
            throw new DomainException('Имя роли не может быть пустым');
        }

        $this->id = $id;
        $this->name = $name;
    }
}