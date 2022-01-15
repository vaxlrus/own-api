<?php

namespace App\Services;

use App\Repository\RoleRepository;
use App\Models\Role;
use App\Components\Api\Response;
use Exception;

class RoleService
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository, Response $response)
    {
        $this->response = $response;
        $this->roleRepository = $roleRepository;
    }

    // Получить название роли по ID
    public function getById(int $id): string
    {
        $role = $this->roleRepository->loadOne($id);

        return $role->getName();
    }

    // Получить роль 
    public function get(int $id): Role
    {
        try
        {
            return $this->roleRepository->loadOne($id);
        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }
    }

    // Получить все роли
    public function getAll(): array
    {
        try
        {
            return $this->roleRepository->loadAll();
        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }
    }

    // Создать роль
    public function create(string $name): Role
    {
        if (strlen($name) === 0)
        {
            $this->response->sendError('Название роли не может быть пустым');
        }

        // Проверить роль на существование
        $rolesList = $this->getAll();

        foreach ($rolesList as $roleObject)
        {
            // Если добавляемая роль совпадает хоть с одной другой имеющейся
            if ($name === $roleObject->getName())
            {
                $this->response->sendError('Роль \'' . $name . '\' уже существует');
            }
        }

        // Если роль не существующая, то создать
        $addRole = $this->roleRepository->create($name);

        return $this->get($addRole);
    }

    // Обновить роль
    public function update(int $id, string $name): Role
    {
        if ($id < 0)
        {
            $this->response->sendError('ID роли не может быть отрицательным');
        }

        try
        {
            // Попытаться обновить роль
            $updateRole = $this->roleRepository->update($id, $name);


        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }

        // Если количество задействованных строк равно единице значит запрос сработал
        if ($updateRole !== 1)
        {
            $this->response->sendError('Произошла ошибка при обновлении роли');
        }

        return $this->get($id);
    }

    // Удалить роль
    public function delete(int $id): array
    {
        if ($id < 0)
        {
            $this->response->sendError('ID роли не может быть отрицательным');
        }

        try
        {
            $deleteRole = $this->roleRepository->deleteOne($id);

            // Если true
            if ($deleteRole)
            {
                $result = [
                    'status' => true,
                    'message' => 'Роль с ID = ' . $id . ' удален'
                ];
            }
        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }

        return  $result;
    }

    // Проверка на наличие роли
    public function assertRoleIsExists(int $id): bool
    {
        $role = $this->get($id);

        if (!is_object($role))
        {
            $this->response->sendError('Роли с ID = '. $id . ' не существует');
        }

        return true;
    }

    // Конвертация в понятный массив
    public function convertToArray($input): array
    {
        // Если это единичный объект
        if (is_object($input))
        {
            $result = $this->convertObjectToArray($input);
        }
        // Если это массив из объектов
        else if (is_array($input))
        {
            $result = [];

            foreach ($input as $user)
            {
                $result[] = $this->convertObjectToArray($user);
            }

            return $result;
        }

        return $result;
    }

    // Конвертирование объекта в массив
    private function convertObjectToArray(Role $role): array
    {
        $result = [
            'id' => $role->getId(),
            'name' => $role->getName()
        ];

        return $result;
    }
}