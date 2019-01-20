<?php

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class ProjectCategory extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'ProjectCategory',
            'description' => 'Project Category',
            'fields' => function () {
                return [
                    'id' =>  Type::nonNull(Type::string()),
                    'name' => Type::string(),
                ];
            },
        ];

        parent::__construct($config);
    }

}
