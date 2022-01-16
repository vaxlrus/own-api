<?php

namespace App\Services;

use App\Repository\RoleRepository;
use App\Models\Role;
use App\Components\Api\Response;
use App\Exceptions\NotFoundException;
use Exception;

class RoleService
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository, Response $response)
    {
        $this->response = $response;
        $this->roleRepository = $roleRepository;
    }

    // Получить роль 
    public function get(string $id): Role
    {
        // Получить роль
        $role = $this->roleRepository->loadOne($id);

        // Проверить ее наличие
        if (!isset($role))
        {
            throw new NotFoundException('Роль ' . $id .' не найдена');
        }

        return $role;
    }

    // Получить все роли
    public function getAll(): array
    {
        $rolesList = $this->roleRepository->loadAll();

        if (!is_array($rolesList) OR empty($rolesList))
        {
            throw new NotFoundException('Роли не найдены');
        }
         
        return $rolesList;
    }

    // Создать роль
    public function create(string $name): Role
    {
       // Проверить на наличие роли
       $roleIsExists = $this->roleRepository->loadByName($name);

       // Если вернулся объект и поле name у него совпадает с создаваемым
       if (is_object($roleIsExists))
       {
           throw new Exception('Роль \'' . $name . '\' уже существует');
       }

        // Если роль не существующая, то создать
        $addRole = $this->roleRepository->createOne($name);

        // Вернуть ответ пришедшиий из репозитория
        return $addRole;
    }

    // Обновить роль
    public function update(string $id, string $name): Role
    {
        // Проверить наличие роли
        $roleIsExists = $this->roleRepository->loadOne($id);

        // Если false
        if (!$roleIsExists)
        {
            throw new NotFoundException('Роль ' . $name . " не найдена");
        }

        // Обновление роли
        $updateRole = $this->roleRepository->updateOne($id, $name);

        return $this->get($id);
    }

    // Удалить роль
    public function delete(string $id): Role
    {
        $deletedRole = $this->roleRepository->loadOne($id);
        $deleteRole = $this->roleRepository->deleteOne($id);

        // Если удаление произошло
        if (!$deleteRole)
        {
            throw new Exception('Роль с ID . ' . $id . ' не удалена');
        }
        
        return $deletedRole;
    }
}