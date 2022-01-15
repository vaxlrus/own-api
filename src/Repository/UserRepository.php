<?php

namespace App\Repository;

use App\Repository\Repository;
use App\Models\User;
use \Exception;
use \PDO;

final class UserRepository extends Repository
{
    const TABLE = 'users';

    // Получить пользователя по ID
    public function loadOne(int $id): User
    {
        $result = parent::load($id);

        // Если БД вернула bool, значит нету данных
        if (is_bool($result))
        {
            throw new Exception('Пользователь с ID = ' . $id . ' не найден');
        }

        // Конвертация массива из БД в объект
        return $this->convertToObject($result);
    }

    // Получить всех пользователей
    public function loadAll(int $limit = 5): array
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

    // Создать пользователя
    public function create(string $name, int $roleId): int
    {
        $query = $this->qb->insert()
            ->into(self::TABLE)
            ->cols(['name', 'role_id'])
            ->bindValues([
                'name' => $name,
                'role_id' => $roleId
            ]);

        $statement = $this->qb->doQuery($query);

        // Получить идентификатор вставленного ID
        return $this->qb->getLastInsertId();
    }

    // Удалить пользователя
    public function deleteOne(int $id): bool
    {
        $deleteUser = parent::delete($id);

        // Если количество затронутых строк равно 1, значит запрос прошел
        if ($deleteUser === 1)
        {
            return true;
        }
        else
        {
            throw new Exception('Пользователя с ID = ' . $id . ' не существует');
        }
    }

    // Обновить пользователя
    public function update(int $id, string $name, int $roleId): int
    {
        $query = $this->qb->update()
            ->table(self::TABLE)
            ->cols(['name', 'role_id'])
            ->where('id = :id')
            ->bindValue('id', $id)
            ->bindValue('name', $name)
            ->bindValue('role_id', $roleId);

        $statement = $this->qb->doQuery($query);

        return $statement->rowCount();
    }

    // Конвертер в объект
    public function convertToObject(array $user): User
    {
        return new User(
            intval($user['id']),
            $user['name'],
            intval($user['role_id'])
        );
    }
}