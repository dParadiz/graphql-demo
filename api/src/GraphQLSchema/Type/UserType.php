<?php

namespace App\GraphQLSchema\Type;

use App\GraphQLSchema\Api;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use App\Project;
use App\User;

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
                        'type' => Type::listOf(Api::$project),
                        'args' => [
                            'limit' => [
                                'type' =>Type::int(),
                                'description' => 'Limit the list of user projects. Default is set to 10',
                                'default' => 10,
                            ],
                            'skipp' => [
                                'type' =>Type::int(),
                                'description' => 'Skipp number of object',
                                'default' => 0,
                            ]

                        ]
                    ],
                    'workingHours' => Type::listOf(Api::$workingHour)
                ];
            },
            'resolveField' => function (User\QueryModel $user, $args, $context, ResolveInfo $info) {
                if ($info->fieldName === 'projects') {
                    return (new Project\Repository)->getProjectByUserId($user->id, $args);
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