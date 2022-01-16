<?php

namespace App\Components;

use \MongoDB\Client;

class Mongo
{
    public function __construct()
    {
        return new Client(
            "mongodb://{$_ENV['MONGO_DB_HOST']}:{$_ENV['MONGO_DB_PORT']}/test?retryWrites=true&w=majority"
        );
    }
}