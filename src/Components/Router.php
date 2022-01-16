<?php

namespace App\Components;

use \FastRoute;
use \FastRoute\Dispatcher;
use \FastRoute\RouteCollector;
use App\Components\Api\Request;
use App\Components\Api\Response;
use ErrorException;
use Exception;

class Router
{
    // Свойство для хранения конфига маршрутов
    private array $routes;

    // Свойство для хранения объекта компонента fast route
    private Dispatcher $dispatcher;

    // Запросы
    private Request $request;

    // Ответы
    private Response $response;

    // Внедрение зависимости
    private \Di\Container $di;

    // Конструктор объекта Router
    public function __construct($pathToConfig, Request $request, Response $response, \DI\Container $di)
    {

        if (!file_exists($pathToConfig))
        {
            throw new Exception('Не задан файл маршрутов для роутера');
        }

        // Получить маршруты
        $this->routes = require $pathToConfig;

        $this->request = $request;
        $this->response = $response;
        $this->di = $di;

        // Если запрос не application/json, выдать ошибку
        // if ($this->request->getAccept() != 'application/json')
        // {
        //     $this->response->sendError('Разрешены запросы только application/json');
        // }

        $this->start();
    }
    
    // Запуск маршрутизации
    private function start() {

        // Получить метод
        $httpMethod = $this->request->getMethod();

        // Получить URI
        $uri = $this->request->getUri();

        // Внести машруты в роутер
        $this->dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $router) {
            foreach ($this->routes as $route)
            {
                // [0] - method, [1] - uri, [2] - handler
                $router->addRoute($route[0], $route[1], $route[2]);
            }
        });
                
        // Маршрутизация
        $this->dispatchRoutes($httpMethod, $uri);
    }

    // Управление маршрутами
    private function dispatchRoutes($httpMethod, $uri): void
    {
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            // Не найден путь
            case Dispatcher::NOT_FOUND:
                throw new ErrorException('Запрашиваемый адрес не найден');
                // $this->response->sendError('Запрашиваемый адрес не найден');
                
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                throw new ErrorException('Метод ' . $httpMethod . ' не разрешен');
                // $this->response->sendError('Метод ' . $httpMethod . ' не разрешен');
                
                break;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                
                $this->di->call($handler, $vars);

                break;
        }
    }
}