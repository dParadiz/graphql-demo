<?php

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class WorkingUnit extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'WorkingUnit',
            'description' => 'Working unit',
            'fields' => function () {
                return [
                    'id' => Type::string(),
                    'email' => Type::string(),

                ];
            },
        ];

        parent::__construct($config);
    }

}
