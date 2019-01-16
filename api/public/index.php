<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Zend\Diactoros\ServerRequestFactory;
use App\RequestHandler\Router;
use App\RequestHandler\Dispatcher;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../config'));
$loader->load('services.yaml');

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$stack = (new Router($containerBuilder))->getStackForUri($request->getUri()->getPath());

(new Dispatcher($stack))->handle($request);

