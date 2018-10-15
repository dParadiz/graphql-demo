<?php
require_once __DIR__ . '/../vendor/autoload.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

use App\GraphQLSchema\HelloWorld;
use App\RequestHandler\Middleware;

$stack = [
    new Middleware\GraphQLMiddleware(new HelloWorld()),
    new Middleware\Authorization(),
];

(new \App\RequestHandler\Dispatcher($stack))->handle($request);

