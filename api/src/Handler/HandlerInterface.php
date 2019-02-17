<?php
declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message;

interface HandlerInterface
{
    /**
     * Process an incoming server request and return a response
     *
     * @param Message\ServerRequestInterface $request
     *
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request): Message\ResponseInterface;
}