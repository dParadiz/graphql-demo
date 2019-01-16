<?php

namespace App\RequestHandler;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;

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