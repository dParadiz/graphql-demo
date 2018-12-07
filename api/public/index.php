<?php
require_once __DIR__ . '/../vendor/autoload.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$stack = (new App\RequestHandler\Router())->getStackForUri($request->getUri()->getPath());

(new App\RequestHandler\Dispatcher($stack))->handle($request);

