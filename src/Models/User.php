<?php

namespace App\Models;

use \DomainException;

class User
{
    private int $id;
    private string $name;
    private int $role_id;

    public function __construct(int $id, string $name, int $role_id)
    {
        if (strlen($name) === 0)
        {
            throw new DomainException('Имя пользователя не может быть пустым');
        }
        
        if ($role_id < 0)
        {
            throw new DomainException('Роль пользователя не может быть отрицательной');
        }

        $this->id = $id;
        $this->name = $name;
        $this->role_id = $role_id;
    }

    // Получить идентификатор
    public function getId(): int
    {
        return $this->id;
    }

    // Получить имя пользователя
    public function getName(): string
    {
        return $this->name;
    }

    // Получить идентификатор роли
    public function getRoleId(): int
    {
        return $this->role_id;
    }
}