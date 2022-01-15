<?php

namespace App\Controllers;

use App\Services\RoleService;
use App\Components\Api\Response;
use App\Components\Api\Request;

class RoleController
{
    private RoleService $roleService;
    private Response $response;
    private Request $request;

    public function __construct(Response $response, RoleService $roleService, Request $request)
    {
        $this->response = $response;
        $this->request = $request;
        $this->roleService = $roleService;
    }

    // Получить роль
    public function get(int $id): void
    {
        // Получить данные из репозитория
        $role = $this->roleService->get($id);

        // Вернуть понятный массив
        $result = $this->roleService->convertToArray($role);

        // Отправить результат
        $this->response->sendSuccess($result);
    }

    // Создать роль
    public function create(): void
    {
        $body = $this->request->getBody();

        $name = $body->name;

        // Отправить запрос на создание
        $newRole = $this->roleService->create($name);

        // Преобразовать ответ в массив
        $result = $this->roleService->convertToArray($newRole);

        // Вернуть результат
        $this->response->sendSuccess($result);
    }

    // Удалить роль
    public function delete(int $id): void
    {
        $result = $this->roleService->delete($id);

        $this->response->sendSuccess($result);
    }

    // Обновить роль
    public function update(int $id): void
    {
        $body = $this->request->getBody();

        $name = $body->name;

        // Отправить запрос на обновление
        $updateRole = $this->roleService->update($id, $name);

        // Преобразовать ответ в массив
        $result = $this->roleService->convertToArray($updateRole);

        // Вернуть результат
        $this->response->sendSuccess($result);
    }

    // Получить все роли
    public function getAll(): void
    {
        // Получить список всех ролей
        $roles = $this->roleService->getAll();

        // Вернуть понятный массив
        $result = $this->roleService->convertToArray($roles);

        // Отправить результат
        $this->response->sendSuccess($result);
    }
}