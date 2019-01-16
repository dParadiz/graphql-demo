<?php

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProjectType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Project',
            'description' => 'Project',
            'fields' => function () {
                return [
                    'id' => Type::string(),
                    'name' => Type::string(),

                ];
            },
        ];

        parent::__construct($config);
    }

}
