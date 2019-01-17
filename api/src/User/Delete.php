<?php

namespace App\User;

use RuntimeException;

class Delete
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

    public function execute($id)
    {
        $deleteResult = $this->userCollection->deleteOne(['id' => $id]);

        if ($deleteResult->getDeletedCount() !== 1) {
            throw new RuntimeException('No user with ' . $id . ' was removed');
        }
    }
}