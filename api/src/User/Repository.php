<?php

namespace App\User;

use MongoDB\Client;

class Repository
{
    /**
     * @var Client
     */
    private $mongoClient;

    public function __construct(Client $client)
    {

        $this->mongoClient = $client;
    }

    public function getUserById(string $id): QueryModel
    {
        $userCollection = $this->mongoClient->trackerApi->users;

        $userData = $userCollection->findOne(['id' => $id]);

        $user = new QueryModel();

        if (null === $userData) {
            return $user;
        }

        $userData = $userData->getArrayCopy();

        $user->id = $userData['id'];
        $user->email = $userData['email'];
        $user->name = $userData['name'];
        $user->roles = $userData['roles'] ?? [];

        return $user;
    }

    public function getUsers(): array
    {
        return [];
    }

    public function create($id, $name, $email, $roles)
    {

        $userCollection = $this->mongoClient->trackerApi->users;

        $userExist = (bool) $userCollection->count(['id' => $id]);

        if ($userExist) {
            return [
                'status' => 'failed',
                'message' => 'User with ' . $id . ' already exists',
            ];
        }

        $insertResult = $userCollection->insertOne([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'roles' => $roles,
        ]);

        if ($insertResult->getInsertedCount() !== 1) {
            return [
                'status' => 'failed',
                'message' => 'Unable to create user',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'User created',
        ];
    }

    public function update(string $id, string $name = null, string $email = null, array $roles = null)
    {

        $userCollection = $this->mongoClient->trackerApi->users;
        $userExist = (bool) $userCollection->count(['id' => $id]);

        if (!$userExist) {
            return [
                'status' => 'failed',
                'message' => 'User with ' . $id . ' does not exists',
            ];
        }
        $updates = [];

        if (null !== $name) {
            $updates['name'] = $name;
        }

        if (null !== $email) {
            $updates['email'] = $email;
        }

        if (null !== $roles) {
            $updates['roles'] = $roles;
        }

        $updateResult = $userCollection->updateOne(['id' => $id], ['$set' => $updates]);

        if ($updateResult->getModifiedCount() !== 1) {

            return [
                'status' => 'failed',
                'message' => 'No user records were updated',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'User was updated',
        ];
    }

    public function remove($id)
    {
        $userCollection = $this->mongoClient->trackerApi->users;

        try {
            $deleteResult = $userCollection->deleteOne(['id' => $id]);
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => $e->getMessage(),
            ];
        }
        if ($deleteResult->getDeletedCount() !== 1) {
            return [
                'status' => 'failed',
                'message' => 'No user with ' . $id . ' was removed',
            ];

        }

        return [
            'status' => 'success',
            'message' => 'User was removed',
        ];
    }
}
