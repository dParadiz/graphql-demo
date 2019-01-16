<?php

namespace App\RequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class Dispatcher
{
    /**
     * @var array|MiddlewareInterface[]
     */
    private $stack;

    /**
     * Dispatcher constructor.
     * @param MiddlewareInterface[] $stack
     */
    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @return RequestHandlerInterface
     */
    private function getRequestHandler(): RequestHandlerInterface
    {
        return array_reduce(
            $this->stack,
            function (RequestHandlerInterface $handler, MiddlewareInterface $middleware) {
                return new class($handler, $middleware) implements RequestHandlerInterface
            {
                    /** @var RequestHandlerInterface */
                    private $handler;
                    /** @var MiddlewareInterface */
                    private $middleware;

                    public function __construct($handler, $middleware)
                {
                        $this->handler = $handler;
                        $this->middleware = $middleware;
                    }

                    public function handle(ServerRequestInterface $request): ResponseInterface
                {
                        return $this->middleware->process($request, $this->handler);
                    }
                };
            },
            new class implements RequestHandlerInterface
        {

                /**
                 * Handle the request and return a response.
                 * @param ServerRequestInterface $request
                 * @return ResponseInterface
                 */
                public function handle(ServerRequestInterface $request): ResponseInterface
            {
                    return new JsonResponse([]);
                }
            });
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function handle(ServerRequestInterface $request)
    {

        $response = $this->getRequestHandler()->handle($request);

        (new SapiEmitter())->emit($response);
    }
}
