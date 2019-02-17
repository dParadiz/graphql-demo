<?php
declare(strict_types=1);

namespace App\Handler;

use GraphQL\{GraphQL as GraphQLFacade, Type\Schema};
use Psr\Http\Message;
use Zend\Diactoros\Response\JsonResponse;

class GraphQL implements HandlerInterface
{
    /** @var Schema */
    private $schema;

    /** @var array */
    private $rootValues;

    /**
     * @param Schema $schema
     * @param array  $rootValues
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
     *
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        try {
            $input = json_decode($request->getBody()->getContents(), true);
            $query = $input['query'];

            $rootValues = $this->rootValues;
            $rootValues['auth'] = $request->getAttribute('auth', null);

            $result = GraphQLFacade::executeQuery(
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
                    'message' => $e->getMessage(),
                ],
            ];
        }

        return new JsonResponse($output);
    }
}
