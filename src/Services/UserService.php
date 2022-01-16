<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Models\User;
use App\Components\Api\Response;
use App\Services\RoleService;
use \Exception;
use App\Exceptions\NotFoundException;

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
    public function getOne(int $id)
    {
        // Получить пользователя по ID
        $user = $this->repo->loadOne($id);

        // Если вернулся не объект
        if (!is_object($user))
        {
            throw new NotFoundException('Пользователь с ID = ' . $id . ' не найден');
        }

        return $user;
    }

    // Получить всех
    public function getAll()
    {
        // Массив с объектами пользователей
        $users = $this->repo->loadAll();

        // Если массив с пользователями пуст, значит их нет
        if (empty($users))
        {
            throw new Exception('Пользователей нет существует');
        }
        
        return $users;
    }

    // Удалить пользователя
    public function delete(int $id): User
    {
        // Удалить пользователя (возвращается true или false)
        $user = $this->repo->loadOne($id);
        $deleteUser = $this->repo->deleteOne($id);

        // Если true
        if (!$deleteUser)
        {
            throw new Exception('Пользователь ' . $id . ' не удален');
        }

        // Если успешно то просто вернуть пустой массив, сконвертируется в 200 код в контроллере
        return $user;
    }

    // Создание нового пользователя
    public function create(string $name, string $roleId): User
    {
        // Попытаться создать нового пользователя
        $newUser = $this->repo->createOne($name, $roleId);

        // Если сервис ролей вернул не объект, то значит роли не существует
        if (!is_object($this->roleService->get($roleId)))
        {
            // $this->response->sendError('Роли с ID = ' . $roleId . ' не существует');
            throw new Exception('Роли с ID = ' . $roleId . ' не существует');
        }

        // Если в ответ пришло число (ID последней вставки в БД)
        // Вернуть новосозданный объект
        return $this->getOne($newUser);
    }

    // Обновление пользователя
    public function update(int $id, string $name, string $roleId): User
    {
        // Проверить существование роли
        $role = $this->roleService->get($roleId);

        // Если роль не является объектом, значит не найдена
        if (!is_object($role))
        {
            throw new Exception('Роль с ID = ' . $roleId . ' не найдена');
        }

        // Попытаться пользователя
        $updateUser = $this->repo->updateOne($id, $name, $roleId);

        // Вернуть обновленный объект
        return $this->getOne($id);
    }
}