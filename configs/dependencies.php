<?php

use App\Components\Database\DatabaseManager;

return [
    DatabaseManager::class => function () {
        return new DatabaseManager(__DIR__ . '/database.php');
    }
];