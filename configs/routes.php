<?php

return [
    ['GET', '/users/{id: \d+}', ['App\Controllers\UserController', 'get']],
    ['DELETE', '/users/{id: \d+}', ['App\Controllers\UserController', 'delete']],
    ['PUT', '/users/{id: \d+}', ['App\Controllers\UserController', 'update']],
    ['POST', '/users', ['App\Controllers\UserController', 'create']],
    ['GET', '/users', ['App\Controllers\UserController', 'getAll']],

    ['GET', '/roles/{id: \w+}', ['App\Controllers\RoleController', 'get']],
    ['DELETE', '/roles/{id: \w+}', ['App\Controllers\RoleController', 'delete']],
    ['PUT', '/roles/{id: \w+}', ['App\Controllers\RoleController', 'update']],
    ['POST', '/roles', ['App\Controllers\RoleController', 'create']],
    ['GET', '/roles', ['App\Controllers\RoleController', 'getAll']],

    ['GET', '/', ['App\Controllers\IndexController', 'ping']],
]

?>