<?php 

use Pimple\Container;

$container = new Container();

$container['graphql-api'] = function($c) {
    return new App\GraphQLSchema\Api();
};

$container['graphql-middleware'] = function($c) {
    return new App\RequestHandler\Middleware\GraphQLMiddleware($c['graphql-api']);
};

$container['authentication-middleware'] = function($c) {
    return new App\RequestHandler\Middleware\Authorization();
};

return $container;