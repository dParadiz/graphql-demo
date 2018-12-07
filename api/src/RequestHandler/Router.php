<?php

namespace App\RequestHandler;

use Psr\Http\Server\MiddlewareInterface;
use App\GraphQLSchema\Api;

class Router
{

    /**
     * @param string $uri
     *
     * @return MiddlewareInterface[]
     */
    public function getStackForUri(string $uri): array
    {
        if ($uri === '/') {
            return [
                new Middleware\GraphQLMiddleware(new Api()),
                new Middleware\Authorization(),
            ];
        }

        if ($uri === '/authenticate') {
            return [
                new Middleware\Authentication(),
            ];
        }
    }
}