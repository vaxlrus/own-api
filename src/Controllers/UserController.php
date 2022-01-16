<?php

namespace App\Controllers;

use App\Components\Api\Response;
use App\Components\Api\Request;
use App\Services\UserService;
use App\Services\RoleService;

class UserController
{
    private UserService $userService;
    private Response $response;
    private Request $request;

    public function __construct(Response $response, UserService $userService, Request $request)
    {
        $this->userService = $userService;
        $this->response = $response;
        $this->request = $request;
    }

    // Получить пользователя
    public function get(int $id): void
    {
        // Объект пользователя
        $user = $this->userService->getOne($id);

        $this->response->sendSuccess($user);
    }

    // Создать пользователя
    public function create()
    {
        $body = $this->request->getBody();

        $name = $body->name;
        $roleId = $body->role_id;

        // Создание пользователя
        $user = $this->userService->create($name, $roleId);

        $this->response->sendSuccess($user);
    }

    // Обновить пользователя
    public function update(int $id)
    {
        $body = $this->request->getBody();

        $name = $body->name;
        $roleId = $body->role_id;

        // Отправить запрос на обновление
        $updateUser = $this->userService->update($id, $name, $roleId);

        // Вернуть результат
        $this->response->sendSuccess($updateUser);
    }

    // Удалить пользователя
    public function delete(int $id)
    {
        $result = $this->userService->delete($id);

        $this->response->sendSuccess($result);
    }

    // Получить всех пользователей
    public function getAll(): void
    {
        $users = $this->userService->getAll();

        $this->response->sendSuccess($users);
    }
}
