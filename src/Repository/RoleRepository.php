<?php

namespace App\Repository;

use App\Models\Role;

final class RoleRepository
{
    private $roles;

    public function __construct(\MongoDB\Client $mongo)
    {
        $this->roles = $mongo->{$_ENV['MONGO_ROLES_DB']}->{$_ENV['MONGO_COLLECTION_NAME']};
    }

    // Получить роль по имени
    public function loadByName(string $name)
    {
        $result = $this->roles->findOne(['name' => $name]);

        // Если вернуло массив
        if (is_object($result))
        {
            return $this->convertToObject($result);
        }
        else
        {
            return null;
        }
    }

    // Получить роль по ID
    public function loadOne(string $id)
    {
        $result = $this->roles->findOne(['role_id' => $id]);
        
        // Если вернуло массив
        if (is_object($result))
        {
            return $this->convertToObject($result);
        }
        else
        {
            return null;
        }
    }

    // Получить все роли
    public function loadAll(): array
    {
        // Получить список всех ролей
        $result = $this->roles->find([]);

        // Список ролей
        $rolesList = [];

        // Переконвертировать массивы в объекты
        foreach ($result as $role)
        {
            $rolesList[] = $this->convertToObject($role);
        }

        // Вернуть список объектов
        return $rolesList;
    }

    // Создать роль
    public function createOne(string $name)
    {
        $id = uniqid();

        // Создать новый документ в коллекции
        $newRole = $this->roles->insertOne(["name" => $name, "role_id" => $id]);

        // Проверить произошла ли вставка
        if (!$newRole->isAcknowledged())
        {
            return null;
        }

        // Получить _id нового документа
        $newRoleId = $newRole->getInsertedId();

        // Получить документ по _id
        $newRole = $this->roles->findOne(['_id' => $newRoleId]);

        // Вернуть объект
        return $this->convertToObject($newRole);
    }

    // Удалить роль
    public function deleteOne(string $id)
    {
        $deleting = $this->roles->deleteOne(["role_id" => $id]);

        // Если не удалено строк, то вернуть null
        if ($deleting->getDeletedCount() === 0)
        {
            return null;
        }

        return true;
    }

    // Обновить роль
    public function updateOne(string $id, string $name)
    {
        $updating = $this->roles->updateOne(
            // Где роль = $id
            ['role_id' => $id],
            // Установить name = $name 
            ['$set' => ['name' => $name]]);

        // Вернуть готовый объект используя метод loadOne (конвертация уже произошла внутри)
        return $this->loadOne($id);
    }

    // Конвертер в объект
    public function convertToObject($role): Role
    {
        return new Role(
            $role['role_id'],
            $role['name'],
        );
    }
}