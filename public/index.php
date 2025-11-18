<?php

require_once __DIR__ . '/../src/includes/session.php';
require_once __DIR__ . '/../src/config/database.php';

startSession();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        '/' => ['controller' => 'HomeController', 'action' => 'index'],
        '/register' => ['controller' => 'AuthController', 'action' => 'showRegister'],
        '/login' => ['controller' => 'AuthController', 'action' => 'showLogin'],
        '/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
        '/rooms' => ['controller' => 'RoomController', 'action' => 'index'],
        '/rooms/create' => ['controller' => 'RoomController', 'action' => 'create'],
        '/rooms/edit' => ['controller' => 'RoomController', 'action' => 'edit'],
        '/rooms/delete' => ['controller' => 'RoomController', 'action' => 'delete']
    ],
    'POST' => [
        '/register' => ['controller' => 'AuthController', 'action' => 'register'],
        '/login' => ['controller' => 'AuthController', 'action' => 'login'],
        '/rooms/store' => ['controller' => 'RoomController', 'action' => 'store'],
        '/rooms/update' => ['controller' => 'RoomController', 'action' => 'update']
    ]
];

if (isset($routes[$method][$uri])) {
    $route = $routes[$method][$uri];
    $controllerName = $route['controller'];
    $action = $route['action'];

    $controllerFile = __DIR__ . "/../src/controllers/{$controllerName}.php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();
        $controller->$action();
    } else {
        http_response_code(404);
        echo "404 - Controller not found";
    }
} else {
    http_response_code(404);
    echo "404 - Page not found";
}
