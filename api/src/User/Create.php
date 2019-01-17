<?php

namespace App\User;

use RuntimeException;

class Create
{
    /**
     * @var \MongoDB\Collection
     */
    private $userCollection;

    /**
     * CreateUser constructor.
     *
     * @param \MongoDB\Collection $collection
     */
    public function __construct(\MongoDB\Collection $collection)
    {
        $this->userCollection = $collection;
    }

    public function execute(string $id, array $userDocument)
    {
        $userExist = (bool)$this->userCollection->countDocuments(['id' => $id]);

        if ($userExist) {
            throw new RuntimeException('User with ' . $id . ' already exists');
        }

        $insertResult = $this->userCollection->insertOne($userDocument);

        if ($insertResult->getInsertedCount() !== 1) {
            throw new RuntimeException('\'Unable to create user\'');
        }
    }

}