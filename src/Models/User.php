<?php

namespace App\Models;

use \DomainException;

class User
{
    public int $id;
    public string $name;
    public string $role_id;

    public function __construct(int $id, string $name, string $role_id)
    {
        if (strlen($name) === 0)
        {
            throw new DomainException('Имя пользователя не может быть пустым');
        }
        
        $this->id = $id;
        $this->name = $name;
        $this->role_id = $role_id;
    }
}