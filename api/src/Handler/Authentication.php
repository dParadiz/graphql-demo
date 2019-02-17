<?php
declare(strict_types=1);

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

            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.h6ft-hLgByiDqa1dnFYkxH-01IHVyWCON5pC_hOfm_c',
        ]);
    }
}