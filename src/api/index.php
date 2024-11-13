<?php

require 'config.php';
require 'vendor/autoload.php';

error_log('REQUEST_URI: ' . $_SERVER['REQUEST_URI']);

use FastRoute\RouteCollector;
use App\Core\Response;

// CORS-støtte
function cors() {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        }
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        exit(0);
    }
}
cors();

// Create routes
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    // Group for /api routes
    $r->addGroup('/api', function (RouteCollector $group) {
        // Books routes
        $group->addRoute('GET', '/books', ['App\Books\Get', 'list']);
        $group->addRoute('GET', '/books/{id:\d+}', ['App\Books\Get', 'get']);

        // Movies routes
        $group->addRoute('GET', '/movies', ['App\Movies\Get', 'list']);
        $group->addRoute('GET', '/movies/{id:\d+}', ['App\Movies\Get', 'get']);
    });
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$routeInfo = $dispatcher->dispatch($httpMethod, parse_url($uri, PHP_URL_PATH));

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        Response::html('<p>Route not found</p>')->send();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        Response::html('<p>Method not allowed</p>')->send();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        // Call the addRoute class
        [$class, $method] = $handler;
        $controller = new $class();
        echo $controller->$method(...array_values($vars));
        break;
}
