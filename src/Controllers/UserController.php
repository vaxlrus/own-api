<?php

namespace App\Controllers;

use App\Components\Api\Response;
use App\Components\Api\Request;
use App\Services\UserService;
use App\Services\RoleService;

class UserController
{
    private UserService $userService;
    private RoleService $roleService;
    private Response $response;
    private Request $request;

    public function __construct(Response $response, UserService $userService, RoleService $roleService, Request $request)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->response = $response;
        $this->request = $request;
    }

    // Получить пользователя
    public function get(int $id): void
    {
        // Объект пользователя
        $user = $this->userService->getOne($id);

        // Конвертирование в понятный массив
        $result = $this->userService->convertToArray($user);

        $this->response->sendSuccess($result);
    }

    // Создать пользователя
    // public function create(string $name, int $roleId)
    public function create()
    {
        $body = $this->request->getBody();

        $name = $body->name;
        $roleId = $body->role_id;

        // Создание пользователя
        $user = $this->userService->create($name, $roleId);

        // Конвертирование в понятный массив
        $result = $this->userService->convertToArray($user);

        $this->response->sendSuccess($result);
    }

    // Обновить пользователя
    public function update(int $id)
    {
        $body = $this->request->getBody();

        $name = $body->name;
        $roleId = $body->role_id;

        // Отправить запрос на обновление
        $updateUser = $this->userService->update($id, $name, $roleId);

        // Преобразовать ответ в массив
        $result = $this->userService->convertToArray($updateUser);

        // Вернуть результат
        $this->response->sendSuccess($result);
    }

    // Удалить пользователя
    public function delete(int $id)
    {
        $result = $this->userService->delete($id);

        $this->response->sendSuccess($result);
    }

    // Получить всех пользователей
    public function getAll(int $limit = 5): void
    {
        $users = $this->userService->getAll($limit);

        // Конвертирование в понятный массив
        $result = $this->userService->convertToArray($users);

        $this->response->sendSuccess($result);
    }
}
