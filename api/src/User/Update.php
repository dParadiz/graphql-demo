<?php

namespace App\User;

use RuntimeException;

class Update
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

        if (!$userExist) {
            throw new RuntimeException('User with ' . $id . ' does not exists');
        }

        unset($userDocument['id']);

        $updateResult = $this->userCollection->updateOne(['id' => $id], ['$set' => $userDocument]);

        if ($updateResult->getModifiedCount() !== 1) {
            throw new RuntimeException('No user records were updated');
        }
    }
}