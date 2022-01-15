<?php

namespace App\Components\Database;

use Aura\SqlQuery\QueryFactory;
use \PDO;
use \Exception;

class DatabaseManager
{
    private $config;

    public function __construct(string $pathToConfig)
    {
        if (!file_exists($pathToConfig))
        {
            throw new Exception('Не задан путь к конфигурации базы данных');
        }
        
        $this->config = require $pathToConfig;
    }


    public function makeConnection(): PDO
    {
        return new PDO(
            $this->getDsnString(),
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
    }

    public function makeFactory(): QueryFactory
    {
        return new QueryFactory(
            $_ENV['DB_DRIVER']
        );
    }

    private function getDsnString(): String
    {
        $template = '%driver%:dbname=%dbname%;host=%host%';

        // Заменить строку на переменные окружения
        $string = str_replace(
            [
                '%driver%',
                '%dbname%',
                '%host%'
            ],

            [
                $_ENV['DB_DRIVER'],
                $_ENV['DB_DATABASE'],
                $_ENV['DB_HOSTNAME']
            ],

            $template
        );

        return $string;
    }
}