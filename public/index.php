<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);

$uri = $_SERVER['REQUEST_URI'];

$routes = include dirname(__DIR__) . '/routes/routes.php';

$action = null;
if (isset($routes[$uri])) {
    $action = $routes[$uri];
}
if (isset($routes[ltrim($uri, '/')])) {
    $action = $routes[ltrim($uri, '/')];
}

if (is_null($action)) {
    http_response_code(404);
    die();
}

try {
    $response = call_user_func([new $action[0], $action[1]]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode($e->getMessage());
    die();
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
die();
