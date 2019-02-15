<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Authorization implements MiddlewareInterface
{

    private $authStrategy;

    // TODO define strategy interface and use it
    public function __construct($authStrategy = null)
    {
        $this->authStrategy = $authStrategy;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //TODO check
        //$this->authStrategy->isAuthorized($request);

        return $handler->handle($request);
    }
}
