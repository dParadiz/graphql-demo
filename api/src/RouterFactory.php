<?php

namespace App;

use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Container\ContainerInterface;

class RouterFactory
{
    public static function getRooter(ContainerInterface $container): Router
    {
        $router = new Router;

        $router->setStrategy(new JsonStrategy($container->get('Psr\Http\Message\ResponseFactoryInterface')));

        $router->map('*', '/', $container->get('App\Handler\GraphQL'))
            ->middleware($container->get('App\Middleware\Authorization'));

        $router->map('POST', '/authenticate', $container->get('App\Handler\Authentication'));

        return $router;
    }
}