<?php

namespace App\Controllers;

use App\Services\RoleService;
use App\Components\Api\Response;
use App\Components\Api\Request;
use Exception;

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
    public function get(string $id): void
    {
        // Получить данные из репозитория
        $role = $this->roleService->get($id);

        // Отправить результат
        $this->response->sendSuccess($role);
    }

    // Создать роль
    public function create(): void
    {
        $body = $this->request->getBody();

        $name = $body->name;

        if (!isset($name))
        {
            throw new Exception('Не указан \'name\'');
        }

        // Отправить запрос на создание
        $newRole = $this->roleService->create($name);

        // Вернуть результат
        $this->response->sendSuccess($newRole);
    }

    // Удалить роль
    public function delete(string $id): void
    {
        $result = $this->roleService->delete($id);

        $this->response->sendSuccess($result);
    }

    // Обновить роль
    public function update(string $id): void
    {
        $body = $this->request->getBody();

        $name = $body->name;

        // Отправить запрос на обновление
        $updateRole = $this->roleService->update($id, $name);

        // Вернуть результат
        $this->response->sendSuccess($updateRole);
    }

    // Получить все роли
    public function getAll(): void
    {
        // Получить список всех ролей
        $roles = $this->roleService->getAll();

        // Отправить результат
        $this->response->sendSuccess($roles);
    }
}