<?php

namespace App\Models;

class Role
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        if ($id < 0)
        {
            throw new DomainException('Роль пользователя не может быть отрицательной');
        }

        if (strlen($name) === 0)
        {
            throw new DomainException('Имя роли не может быть пустым');
        }

        $this->id = $id;
        $this->name = $name;
    }

    // Получить наименование роли
    public function getName(): string
    {
        return $this->name;
    }

    // Получить идентификатор роли
    public function getId(): int
    {
        return $this->id;
    }
}