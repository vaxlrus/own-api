<?php

namespace App\Repository;

use App\Repository\Repository;
use App\Models\Role;
use \Exception;

final class RoleRepository extends Repository
{
    const TABLE = 'roles';

    // Получить роль по ID
    public function loadOne(int $id): Role
    {
        $result = parent::load($id);

        // Если БД вернула bool, значит нету данных
        if (is_bool($result))
        {
            throw new Exception('Роль с ID: ' . $id . ' не найдена');
        }

        return $this->convertToObject($result);
    }

    // Получить все роли
    // 100 указал т.к ролей в любом случае не будет 100 скорее всего, но раз метод родительский един для всех, то некий компромисс
    public function loadAll(int $limit = 100): array
    {
        if ($limit <= 0)
        {
            throw new Exception('Не задан лимитер для SQL запроса');
        }

        $result = parent::loadMany($limit);

        // Если ответ пустой
        if (is_array($result) AND count($result) === 0)
        {
            throw new Exception('Пользователей не существует');
        }

        $userList = [];

        foreach ($result as $user)
        {
            $userList[] = $this->convertToObject($user);
        }

        return $userList;
    }

    // Создать роль
    public function create(string $name)
    {
        $query = $this->qb->insert()
            ->into(self::TABLE)
            ->cols(['name'])
            ->bindValue('name', $name);

        $statement = $this->qb->doQuery($query);

        // Получить идентификатор вставленного ID
        return $this->qb->getLastInsertId();
    }

    // Удалить роль
    public function deleteOne(int $id): bool
    {
        // Результат запроса
        $result = parent::delete($id);

        // Если затронуто строк больше чем 0, значит запрос выполнен
        if ($result === 1)
        {
            return true;
        }
        else
        {
            throw new Exception('Роли с ID = ' . $id . ' не существует');
        }
    }

    // Обновить роль
    public function update(int $id, string $name): int
    {
        $query = $this->qb->update()
            ->table(self::TABLE)
            ->cols(['name'])
            ->where('id = :id')
            ->bindValue('id', $id)
            ->bindValue('name', $name);

        $statement = $this->qb->doQuery($query);

        return $statement->rowCount();
    }

    // Конвертер в объект
    public function convertToObject(array $role): Role
    {
        return new Role(
            intval($role['id']),
            $role['name'],
        );
    }
}