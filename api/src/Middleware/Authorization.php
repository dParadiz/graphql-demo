<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Middleware\Authorization\Strategy\StrategyInterface;
use Psr\Http\{
    Message\ResponseInterface,
    Message\ServerRequestInterface,
    Server\MiddlewareInterface,
    Server\RequestHandlerInterface
};
use League\Route\Http\Exception\UnauthorizedException;

class Authorization implements MiddlewareInterface
{
    /**
     * @var StrategyInterface
     */
    private $authStrategy;


    public function __construct(StrategyInterface $authStrategy = null)
    {
        $this->authStrategy = $authStrategy;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws UnauthorizedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if (!$this->authStrategy->isAuthorized($request)) {
            //todo consider to use custom app strategy to handle app specific exceptions
            throw new UnauthorizedException();
        };

        return $handler->handle($request);
    }
}
