<?php
declare(strict_types=1);

namespace App\GraphQLSchema;

use App\GraphQLSchema\Type\MutationResponse;
use App\Project;
use App\User;
use GraphQL\Type\{Definition\ObjectType, Definition\ResolveInfo, Definition\Type, Schema};
use MongoDB\{Client, Model\BSONDocument};

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
                    'resolve' => function ($context, $args) {
                        $collection = $this->mongoClient->trackerApi->projects;

                        $project = $collection->findOne(['id' => $args['id']]);

                        return $project;
                    },
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
                'project' => [
                    'type' => new ObjectType([
                        'name' => 'projectManagementOperations',
                        'fields' => [
                            'create' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::nonNull(Type::string())],
                                    'description' => ['type' => Type::string()],
                                ],
                            ],
                            'update' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'name' => ['type' => Type::string()],
                                    'description' => ['type' => Type::string()],
                                ],
                            ],
                            'remove' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                ],

                            ],
                            'addOrUpdateMember' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'member' => ['type' => TypeRegistry::projectMember()],

                                ],
                            ],
                            'removeMember' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'member' => ['type' => Type::nonNull(TypeRegistry::projectMember())],
                                ],
                            ],
                            'addCategory' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'category' => ['type' => Type::nonNull(TypeRegistry::projectCategory())],
                                ],
                            ],
                            'removeCategory' => [
                                'type' => TypeRegistry::mutationResponse(),
                                'args' => [
                                    'id' => ['type' => Type::nonNull(Type::string())],
                                    'category' => ['type' => Type::nonNull(TypeRegistry::projectCategory())],
                                ],
                            ],
                        ],
                        'resolveField' => function ($data, $args, $context, ResolveInfo $info) {
                            $projectCollection = $this->mongoClient->trackerApi->projects;
                            $userCollection = $this->mongoClient->trackerApi->users;

                            $id = $args['id'];

                            if ($info->fieldName === 'create') {

                                $document = array_filter($args);
                                try {
                                    (new Project\Create($projectCollection, $userCollection))->execute($id, $document);

                                    return new MutationResponse('success', 'Project created');
                                } catch (\Exception $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'update') {
                                try {
                                    $document = array_filter($args);
                                    (new Project\Update($projectCollection, $userCollection))->execute($id, $document);

                                    return new MutationResponse('success', 'Project was updated');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'remove') {
                                try {
                                    (new Project\Delete($projectCollection))->execute($id);

                                    return new MutationResponse('success', 'Project was removed');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'addOrUpdateMember') {
                                try {
                                    (new Project\AddOrUpdateMember($projectCollection, $userCollection))->execute($id, $args['member']);
                                    return new MutationResponse('success', 'Project members were updated');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'removeMember') {
                                try {
                                    (new Project\RemoveMember($projectCollection))->execute($id, $args['member']);
                                    return new MutationResponse('success', 'Project members were updated');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'addCategory') {
                                try {
                                    (new Project\AddCategory($projectCollection))->execute($id, $args['category']);
                                    return new MutationResponse('success', 'Project categories were updated');
                                } catch (\RuntimeException $e) {
                                    return new MutationResponse('failed', $e->getMessage());
                                }
                            }

                            if ($info->fieldName === 'removeCategory') {
                                try {
                                    (new Project\RemoveCategory($projectCollection))->execute($id, $args['category']);
                                    return new MutationResponse('success', 'Project categories were updated');
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
