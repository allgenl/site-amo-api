<?php

use App\Controller\CreateController;
use App\Controller\HomeController;
use Laminas\Diactoros\ServerRequestFactory;

require_once __DIR__ . "/../bootstrap.php";

session_start();

const TOKEN_FILE = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'oauth' . DIRECTORY_SEPARATOR . 'token_info.json';

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new League\Route\Router;

// Routes
$router->get('/', HomeController::class);
$router->post('/create', CreateController::class);

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);


