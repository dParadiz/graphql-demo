<?php
declare(strict_types=1);

namespace App;

use App\Handler\{Authentication, GraphQL};
use App\Middleware\Authorization;
use League\Route\{Router, Strategy\JsonStrategy};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class RouterFactory
{
    /**
     * @param ContainerInterface $container
     * @return Router
     */
    public static function getRooter(ContainerInterface $container): Router
    {
        $router = new Router;

        $router->setStrategy(new JsonStrategy($container->get(ResponseFactoryInterface::class)));

        $router->map('*', '/', $container->get(GraphQL::class))
            ->middleware($container->get(Authorization::class));

        $router->map('POST', '/authenticate', $container->get(Authentication::class));

        return $router;
    }
}