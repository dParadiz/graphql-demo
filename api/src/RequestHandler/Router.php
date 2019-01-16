<?php

namespace App\RequestHandler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class Router
{
    /**
     * @var \Pimple\Container
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @param string $uri
     *
     * @return MiddlewareInterface[]
     */
    public function getStackForUri(string $uri): array
    {

        if ($uri === '/') {
            return [
                $this->container->get('graphql_middleware'),
                $this->container->get('authentication_middleware'),
            ];
        }

        if ($uri === '/authenticate') {
            return [
                $this->container('authentication_middleware'),
            ];
        }
    }
}
