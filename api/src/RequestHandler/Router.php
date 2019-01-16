<?php

namespace App\RequestHandler;

use Psr\Http\Server\MiddlewareInterface;

class Router
{
    /**
     * @var \Pimple\Container
     */
    private $container;

    public function __construct(\Pimple\Container $container)
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
                $this->container['graphql-middleware'],
                $this->container['authentication-middleware'],
            ];
        }

        if ($uri === '/authenticate') {
            return [
                $this->container['authentication-middleware'],
            ];
        }
    }
}