<?php

namespace App\GraphQLSchema\Type;


use App\GraphQLSchema\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use App\Project;
use App\User;

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
            }
        ];

        parent::__construct($config);
    }

}