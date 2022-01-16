<?php

namespace App\Repository;

use App\Repository\Repository;
use App\Models\User;
use App\Components\Database\QueryBuilder;

final class UserRepository extends Repository
{
    const TABLE = 'users';

    public function __construct(QueryBuilder $qb)
    {
        parent::__construct($qb, self::TABLE);
    }

    // Получить пользователя по ID
    public function loadOne(int $id): User
    {
        $result = parent::load($id);

        // Конвертация массива из БД в объект
        return $this->convertToObject($result);
    }

    // Получить всех пользователей
    public function loadAll(): array
    {
        // Получить список пользователей
        $result = parent::loadMany();

        $userList = [];

        // Преобразование массива с пользователями в массив с объектами пользователей
        foreach ($result as $user)
        {
            $userList[] = $this->convertToObject($user);
        }

        // Вернуть массив с объектами пользователей
        return $userList;
    }

    // Создать пользователя
    public function createOne(string $name, string $roleId): int
    {
        // Формирование объекта запроса на вставку
        $query = $this->qb->insert()
            ->into(self::TABLE)
            ->cols(['name', 'role_id'])
            ->bindValues([
                'name' => $name,
                'role_id' => $roleId
            ]);

        // Выполнение запроса на вставку
        $this->qb->doQuery($query);

        // Получить идентификатор вставленного ID
        return $this->qb->getLastInsertId();
    }

    // Удалить пользователя
    public function deleteOne(int $id): bool
    {
        $deleteUser = parent::delete($id);

        // Если количество затронутых строк не равно 1, значит запрос не прошел
        if (!$deleteUser === 1)
        {
            return false;
        }

        return true;
    }

    // Обновить пользователя
    public function updateOne(int $id, string $name, string $roleId): int
    {
        // Формирование объекта запроса на обновление пользователя
        $query = $this->qb->update()
            ->table(self::TABLE)
            ->cols(['name', 'role_id'])
            ->where('id = :id')
            ->bindValue('id', $id)
            ->bindValue('name', $name)
            ->bindValue('role_id', $roleId);

        // Выполнение запроса
        $statement = $this->qb->doQuery($query);

        // Возвратить количество задействованных строк
        return $statement->rowCount();
    }

    // Конвертер в объект
    public function convertToObject(array $user): User
    {
        return new User(
            intval($user['id']),
            $user['name'],
            $user['role_id']
        );
    }
}