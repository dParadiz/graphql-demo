<?php

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class WorkingHour extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'WorkingHour',
            'description' => 'Working hour',
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