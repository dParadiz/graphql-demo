<?php

namespace App\GraphQLSchema\Type;

use App\GraphQLSchema\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UserType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'User',
            'description' => 'Our blog authors',
            'fields' => function () {
                return [
                    'id' => Type::string(),
                    'name' => Type::string(),
                    'email' => Type::string(),
                    'roles' => Type::listOf(Type::string()),
                    'projects' => [
                        'type' => Type::listOf(TypeRegistry::project()),
                        'args' => [
                            'limit' => [
                                'type' => Type::int(),
                                'description' => 'Limit the list of user projects.',
                                'default' => 5,
                            ],
                            'skipp' => [
                                'type' => Type::int(),
                                'description' => 'Skipp number of object',
                                'default' => 0,
                            ],

                        ],
                    ],
                    'workingHours' => Type::listOf(TypeRegistry::workingUnit()),
                ];
            },
            'resolveField' => function ($user, $args, $context, ResolveInfo $info) {
                if ($info->fieldName === 'projects') {
                    return [];
                }

                if ($info->fieldName === 'workingHours') {
                    return [];
                }

                return $user->{$info->fieldName};
            },

        ];

        parent::__construct($config);
    }

}
