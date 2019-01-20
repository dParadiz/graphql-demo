<?php

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class ProjectMember extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'ProjectMember',
            'description' => 'Project Member',
            'fields' => function () {
                return [
                    'id' => Type::nonNull(Type::string()),
                    'roles' => Type::listOf(Type::string()),
                ];
            },
        ];

        parent::__construct($config);
    }

}
