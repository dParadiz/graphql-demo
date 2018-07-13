<?php
require_once __DIR__ . '/../vendor/autoload.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);


$stack = [
    new App\RequestHandler\Middleware\Authorization()
];

(new \App\RequestHandler\App($stack))->handle($request);

