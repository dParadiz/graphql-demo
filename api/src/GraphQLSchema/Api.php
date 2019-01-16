<?php

namespace App\GraphQLSchema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

use App\User;
use App\Project;

class Api extends Schema
{
    /**
     * @var User\Repository $userRegistry
     */
    private $userRegistry;

    /**
     * @var Project\Repository $projectRepository
     */
    private $projectRepository;


    public function __construct(User\Repository $userRegistry, Project\Repository $projectRepository)
    {
        $this->userRegistry = $userRegistry;
        $this->projectRepository = $projectRepository;

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
                    'type' => TypeRegistry::user($this->projectRepository),
                    'args' => [
                        'id' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function ($context, $args) {
                        return $this->userRegistry->getUserById($args['id']);
                    }
                ],
                'users' => [
                    'type' => Type::listOf(TypeRegistry::user($this->projectRepository)),
                    'args' => [
                        'role' => Type::string()
                    ],
                    'resolve' => function ($context, $args) {
                        return $this->userRegistry->getUsers();
                    }

                ],
                'project' => [
                    'type' => TypeRegistry::project(),
                    'args' => [
                        'id' => Type::string(),
                    ]
                ],
                'projects' => [
                    'type' => Type::listOf(TypeRegistry::project()),
                    'args' => [
                        'id' => Type::string(),
                    ]
                ],
                'workingUnit' => [
                    'type' => TypeRegistry::workingUnit(),
                    'args' => [
                        'id' => Type::string(),
                    ]
                ],
                'workingUnits' => [
                    'type' => Type::listOf(TypeRegistry::workingUnit()),
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
                                'type' =>  TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::string()],
                                    'email' => ['type' => Type::string()],
                                    'roles' => ['type' => Type::listOf(Type::string())]
                                ],
                            ],
                            'update' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::string()],
                                    'email' => ['type' => Type::string()],
                                    'roles' => ['type' => Type::listOf(Type::string())]
                                ],
                            ],
                            'remove' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())]
                                ],

                            ],
                        ],
                        'resolveField' => function ($data, $args, $context, ResolveInfo $info) {

                            if ($info->fieldName === 'create') {
                                return $this->userRegistry->create($args['id'], $args['name'], $args['email'], $args['roles']);
                            }

                            if ($info->fieldName === 'update') {
                                return $this->userRegistry->update($args['id'], $args['name'], $args['email'], $args['roles']);
                            }

                            if ($info->fieldName === 'remove') {
                                return $this->userRegistry->remove($args['id']);
                            }

                            return '';
                        },
                    ]),
                    'resolve' => function ($context, $args) {
                        // user field resolver
                        // maybe permission check
                        // return null will skip field resolvers
                        return '';
                    },
                ]
            ],

        ]);
    }
}
