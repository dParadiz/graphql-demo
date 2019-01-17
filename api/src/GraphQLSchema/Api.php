<?php

namespace App\GraphQLSchema;

use App\GraphQLSchema\Type\MutationResponse;
use App\User;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use MongoDB\Client;
use MongoDB\Model\BSONDocument;

class Api extends Schema
{
    /**
     * @var Client
     */
    private $mongoClient;

    public function __construct(Client $client)
    {
        $this->mongoClient = $client;

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
                    'type' => TypeRegistry::user(),
                    'args' => [
                        'id' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($context, $args) {
                        $userCollection = $this->mongoClient->trackerApi->users;

                        $userData = $userCollection->findOne(['id' => $args['id']]);

                        return $userData;
                    },
                ],
                'users' => [
                    'type' => Type::listOf(TypeRegistry::user()),
                    'args' => [
                        'role' => Type::string(),
                    ],
                    'resolve' => function ($context, $args) {
                        $userCollection = $this->mongoClient->trackerApi->users;

                        $userList = [];
                        /** @var BSONDocument $user */
                        foreach ($userCollection->find() as $user) {
                            $userList[] = $user;
                        }

                        return $userList;
                    },

                ],
                'project' => [
                    'type' => TypeRegistry::project(),
                    'args' => [
                        'id' => Type::string(),
                    ],
                ],
                'projects' => [
                    'type' => Type::listOf(TypeRegistry::project()),
                    'args' => [
                        'id' => Type::string(),
                    ],
                ],
                'workingUnit' => [
                    'type' => TypeRegistry::workingUnit(),
                    'args' => [
                        'id' => Type::string(),
                    ],
                ],
                'workingUnits' => [
                    'type' => Type::listOf(TypeRegistry::workingUnit()),
                    'args' => [
                        'id' => Type::string(),
                    ],
                ],
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
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::string()],
                                    'email' => ['type' => Type::string()],
                                    'roles' => ['type' => Type::listOf(Type::string())],
                                ],
                            ],
                            'update' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::string()],
                                    'email' => ['type' => Type::string()],
                                    'roles' => ['type' => Type::listOf(Type::string())],
                                ],
                            ],
                            'remove' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                ],

                            ],
                        ],
                        'resolveField' => function ($data, $args, $context, ResolveInfo $info) {
                            $userCollection = $this->mongoClient->trackerApi->users;

                            $id = $args['id'];
                            $userDocument = array_filter($args);

                            if ($info->fieldName === 'create') {
                                try {
                                    (new User\Create($userCollection))->execute($id, $userDocument);

                                    return new MutationResponse('success', 'User created');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'update') {
                                try {
                                    (new User\Update($userCollection))->execute($id, $userDocument);

                                    return new MutationResponse('success', 'User was updated');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'remove') {
                                try {
                                    (new User\Delete($userCollection))->execute($id);

                                    return new MutationResponse('success', 'User was removed');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            return '';
                        },
                    ]),
                    'resolve' => function ($context, $args) {
                        // user field resolver
                        // maybe permission check
                        // return null will skip field resolvers
                        return [];
                    },
                ],
            ],

        ]);
    }
}
