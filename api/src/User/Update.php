<?php

namespace App\User;

use RuntimeException;

class Update
{
    /**
     * @var \MongoDB\Collection
     */
    private $collection;

    /**
     * CreateUser constructor.
     *
     * @param \MongoDB\Collection $collection
     */
    public function __construct(\MongoDB\Collection $collection)
    {
        $this->collection = $collection;
    }

    public function execute(string $id, array $document)
    {
        $exists = (bool) $this->collection->countDocuments(['id' => $id]);

        if (!$exists) {
            throw new RuntimeException('User with ' . $id . ' does not exists');
        }

        unset($document['id']);

        $updateResult = $this->collection->updateOne(['id' => $id], ['$set' => $document]);

        if ($updateResult->getModifiedCount() !== 1) {
            throw new RuntimeException('No user records were updated');
        }
    }
}
