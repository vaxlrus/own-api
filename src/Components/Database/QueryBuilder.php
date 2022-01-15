<?php

namespace App\Components\Database;

use App\Components\Database\DatabaseManager;
use Aura\SqlQuery\QueryFactory;
use Exception;
use \PDO;

class QueryBuilder
{
    private PDO $db;
    private QueryFactory $queryFactory;

    public function __construct(DatabaseManager $db)
    {
        try 
        {
            $this->db = $db->makeConnection();
        }
        catch (\PDOException $error)
        {
            throw new Exception('Ошибка подключения к базе данных: ' . $error->getMessage());
        }

        // Initialize queryfactory from querybuilder
        $this->queryFactory = $db->makeFactory();
    }

    public function select()
    {
        return $this->queryFactory->newSelect();
    }

    public function insert()
    {
        return $this->queryFactory->newInsert();
    }

    public function update()
    {
        return $this->queryFactory->newUpdate();
    }

    public function delete()
    {
        return $this->queryFactory->newDelete();
    }

    public function doQuery($queryObject)
    {
        $statement = $this->db->prepare(
            $queryObject->getStatement()
        );

        $statement->execute(
            $queryObject->getBindValues()
        );

        return $statement;
    }

    public function getLastInsertId(): int
    {
        return $this->db->lastInsertId();
    }
}