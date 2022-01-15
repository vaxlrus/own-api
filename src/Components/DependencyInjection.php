<?php

namespace App\Components;

use \DI\ContainerBuilder;
use \Exception;

class DependencyInjection
{
    public static function create(string $pathToConfig): \DI\Container
    {
        if (!file_exists($pathToConfig))
        {
            throw new Exception('Не указан путь конфигурационным файлам');
        }

        $builder = new ContainerBuilder();
        $builder->addDefinitions(
            require $pathToConfig
        );
        
        return $builder->build();
    }
}