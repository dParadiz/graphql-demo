<?php

namespace App\GraphQLSchema;

use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class HelloWorld extends Schema {

    public function __construct() {

        parent::__construct([
            'query' => $this->getQuery(),
            'mutation' => $this->getMutation,
        ]);
    }

    private function getQuery() : ObjectType {
        return  new ObjectType([
            'name' => 'Query',
            'fields' => [
                'echo' => [
                    'type' => Type::string(),
                    'args' => [
                        'message' => ['type' => Type::string()],
                    ],
                    'resolve' => function ($root, $args) {
                        return ($root['auth']['name'] ?? '') .  ' says '. $args['message']  . ' ' ;
                    }
                ],
            ],
        ]);
    }

    private function getMutation() : ObjectType {
        return new ObjectType([
            'name' => 'Calc',
            'fields' => [
                'sum' => [
                    'type' => Type::int(),
                    'args' => [
                        'x' => ['type' => Type::int()],
                        'y' => ['type' => Type::int()],
                    ],
                    'resolve' => function ($root, $args) {
                        return $args['x'] + $args['y'];
                    },
                ],
            ],
        ]);
    }
}