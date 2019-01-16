<?php

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MutationResponse extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'MutationResponse',
            'fields' => function () {
                return [
                    'status' => Type::string(),
                    'message' => Type::string(),
                ];
            },
        ];

        parent::__construct($config);
    }

}
