<?php

namespace App\GraphQLSchema;

use App\GraphQLSchema\Type\ProjectType;
use App\GraphQLSchema\Type\UserType;
use App\GraphQLSchema\Type\WorkingHour;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

use App\User;

class Api extends Schema
{
    public static $user;
    public static $project;
    public static $workingHour;

    public function __construct()
    {
        self::$user = new UserType();
        self::$project = new ProjectType();
        self::$workingHour = new WorkingHour();

        parent::__construct([
            'query' => $this->getQuery(),
            'mutation' => $this->getMutation(),
        ]);
    }

    private function getQuery(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'user' => [
                    'type' => self::$user,
                    'args' => [
                        'id' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function ($context, $args) {
                        return (new User\Repository())->getUserById($args['id']);
                    }
                ],
                'users' => [
                    'type' => Type::listOf(self::$user),
                    'args' => [
                        'role' => Type::string()
                    ],
                    'resolve' => function ($context, $args) {
                        return (new User\Repository())->getUserById($args['id']);
                    }

                ],
                'project' => [
                    'type' => self::$project,
                    'args' => [
                        'id' => Type::string(),
                    ]
                ],
                'projects' => [
                    'type' => Type::listOf(self::$project),
                    'args' => [
                        'id' => Type::string(),
                    ]
                ],
                'workingHour' => [
                    'type' => self::$workingHour,
                    'args' => [
                        'id' => Type::string(),
                    ]
                ],
                'workingHours' => [
                    'type' => Type::listOf(self::$workingHour),
                    'args' => [
                        'id' => Type::string(),
                    ]
                ]
            ],
        ]);
    }

    private function getMutation(): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'user' => [
                    'type' => new ObjectType([
                        'name' => 'userManagementOperations',
                        'fields' => [
                            'create' => [
                                'type' => Type::int(),
                                'args' => [
                                    'name' => ['type' => Type::string()],
                                    'email' => ['type' => Type::string()],
                                    'roles' => ['type' => Type::listOf(Type::string())]
                                ],
                            ],
                            'update' => [
                                'type' => Type::int(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::string()],
                                    'email' => ['type' => Type::string()],
                                    'roles' => ['type' => Type::listOf(Type::string())]
                                ],
                            ],
                            'remove' => [
                                'type' => Type::int(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())]
                                ],

                            ],
                        ],
                        'resolveField' => function ($data, $args, $context, ResolveInfo $info) {
                            if ($info->fieldName === 'create') {
                                error_log(var_export($args, true));
                                return 1;
                            }

                            if ($info->fieldName === 'update') {
                                return 2;
                            }

                            if ($info->fieldName === 'remove') {
                                return 3;
                            }

                            return -1;
                        },
                    ]),
                    'resolve' => function ($context, $args) {
                        // user field resolver
                        // maybe permission check
                        // return null will skipp field resolvers
                        return '';
                    },
                ]
            ],

        ]);
    }
}