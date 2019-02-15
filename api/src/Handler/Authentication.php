<?php

namespace App\Handler;

use Psr\Http\Message;
use Zend\Diactoros\Response\JsonResponse;

class Authentication implements HandlerInterface
{

    /**
     * Process an incoming server request and return a response
     *
     * @param Message\ServerRequestInterface $request
     *
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        return new JsonResponse([

            'token' => 'token123',
        ]);
    }
}