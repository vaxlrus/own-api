<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../vendor/autoload.php';
require_once '../src/Components/HandlerException.php';

set_exception_handler("myErrorHandler");

use App\Components\Router;
use App\Components\DependencyInjection;
use App\Components\Api\Request;
use App\Components\Api\Response;
use App\Components\Mongo;
use Dotenv\Dotenv;

// Инициализировать переменные окружения
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Создать объект для управления запросами
$request = new Request();

// Объект для управления ответами
$response = new Response();

// Внедрение зависимостей
$di = DependencyInjection::create('../configs/dependencies.php');

// Инициализация MongoDB
$mongo = new Mongo;

// Инициализировать маршрутизатор
$router = new Router('../configs/routes.php', $request, $response, $di);