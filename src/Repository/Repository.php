<?php

namespace App\Repository;

use App\Components\Database\QueryBuilder;

abstract class Repository
{
    protected QueryBuilder $qb;

    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    // Получение единичного элемента
    final protected function load(int $id)
    {
        // Запрос на выборку
        $query = $this->qb->select()
        ->cols(['*'])
        ->from(static::TABLE)
        ->where('id = :id')
        ->bindValue('id', $id);
    
        // Выполнение запроса
        $statement = $this->qb->doQuery($query);

        // Возврат полученных данных
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    // Получение всех элементов
    final protected function loadMany(int $limit)
    {
        // Запрос на выборку
        $query = $this->qb->select()
            ->cols(['*'])
            ->from(static::TABLE)
            ->limit($limit);

        // Выполнение запроса
        $statement = $this->qb->doQuery($query);

        // Возврат полученных данных
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Удаление элемента из таблицы
    final protected function delete(int $id)
    {
        // Запрос на удаление
        $query = $this->qb->delete()
            ->from(static::TABLE)
            ->where('id = :id')
            ->bindValue('id', $id);

        $statement = $this->qb->doQuery($query);

        return $statement->rowCount();
    }
}