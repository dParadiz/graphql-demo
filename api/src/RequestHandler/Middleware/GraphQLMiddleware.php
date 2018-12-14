<?php

namespace App\RequestHandler\Middleware;

use Psr\Http\Message;
use Psr\Http\Server;
use Zend\Diactoros\Response\JsonResponse;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;

class GraphQLMiddleware implements Server\MiddlewareInterface
{
    /** @var Schema */
    private $schema;

    /** @var array */
    private $rootValues;

    /**
     * @param Schema $schema
     * @param array $rootValues
     */
    public function __construct(Schema $schema, array $rootValues = [])
    {
        $this->schema = $schema;
        $this->rootValues = $rootValues;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Message\ServerRequestInterface $request
     * @param Server\RequestHandlerInterface $handler
     *
     * @return Message\ResponseInterface
     */
    public function process(Message\ServerRequestInterface $request, Server\RequestHandlerInterface $handler): Message\ResponseInterface
    {
        $response = $handler->handle($request);

        try {
            $input = json_decode($request->getBody()->getContents(), true);
            $query = $input['query'];

            $rootValues = $this->rootValues;
            $rootValues['auth'] = $request->getAttribute('auth', null);

            $result = GraphQL::executeQuery(
                $this->schema,
                $query,
                array_filter($rootValues),
                null,
                $input['variables'] ?? null
            );

            $output = $result->toArray();

        } catch (\Exception $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage()
                ]
            ];
        }

        return $response->withPayload($output);
    }
}