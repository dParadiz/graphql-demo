<?php
require_once __DIR__ . '/../vendor/autoload.php';
$services = include __DIR__ . '/../config/services.php';


$request = Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$stack = (new App\RequestHandler\Router($services))->getStackForUri($request->getUri()->getPath());

(new App\RequestHandler\Dispatcher($stack))->handle($request);

