<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Models\User;
use App\Components\Api\Response;
use App\Services\RoleService;
use \Exception;

class UserService
{
    private UserRepository $repo;
    private RoleService $roleService;

    public function __construct(UserRepository $repo, RoleService $roleService, Response $response)
    {
        $this->repo = $repo;
        $this->response = $response;
        $this->roleService = $roleService;
    }

    // Получить одного пользователя
    public function getOne(int $id): User
    {
        try
        {
            // Получить пользователя по ID
            return $this->repo->loadOne($id);
        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }
    }

    // Получить всех
    public function getAll(int $limit): array
    {
        try
        {
            return $this->repo->loadAll($limit);
        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }
    }

    // Удалить пользователя
    public function delete(int $id): array
    {
        if ($id < 0)
        {
            $this->response->sendError('ID удаляемой роли не может быть отрицательным');
        }

        try
        {
            $deleteUser = $this->repo->deleteOne($id);

            // Если true
            if ($deleteUser)
            {
                $result = [
                    'status' => true,
                    'message' => 'Пользователь с ID = ' . $id . ' удален'
                ];
            }
        }
        catch (Exception $error)
        {
            $this->response->sendError($error->getMessage());
        }

        return $result;
    }

    // Создание нового пользователя
    public function create(string $name, int $roleId): User
    {
        if (strlen($name) === 0)
        {
            $this->response->sendError("Не задано имя пользователя");
        }

        if ($roleId < 0)
        {
            $this->response->sendError("ID роли не может быть отрицательной");
        }

        // Попытаться создать нового пользователя
        $newUser = $this->repo->create($name, $roleId);

        // Проверка на наличие роли
        if (!$this->roleService->assertRoleIsExists($roleId))
        {
            $this->response->sendError('Роли с ID = ' . $roleId . ' не существует');
        }

        // Если в ответ пришло число (ID последней вставки в БД)
        // Вернуть новосозданный объект
        return $this->getOne($newUser);
    }

    // Обновление пользователя
    public function update(int $id, string $name, int $roleId): User
    {
        if ($id < 0)
        {
            $this->response->sendError('ID пользователя не может быть отрицательным');
        }

        if (strlen($name) === 0)
        {
            $this->response->sendError('Имя пользователя не может быть пустым');
        }

        if ($roleId < 0)
        {
            $this->response->sendError('ID роли не может быть отрицательным');
        }

        // Проверить существование роли
        $role = $this->roleService->get($roleId);

        // Попытаться пользователя
        $updateUser = $this->repo->update($id, $name, $roleId);

        // Если количество задействованных строк равно единице значит запрос сработал
        if ($updateUser !== 1)
        {
            $this->response->sendError('Произошла ошибка при обновлении пользователя');
        }

        return $this->getOne($id);
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
    private function convertObjectToArray(User $user): array
    {
        $result = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'role' =>  $this->roleService->getById($user->getRoleId()),
        ];

        return $result;
    }
}