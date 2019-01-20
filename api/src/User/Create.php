<?php

namespace App\User;

use RuntimeException;

class Create
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

        $exist = (bool)$this->collection->countDocuments(['id' => $id]);

        if ($exist) {
            throw new RuntimeException('User with ' . $id . ' already exists');
        }

        $document['id'] = $id;
        $insertResult = $this->collection->insertOne($document);

        if ($insertResult->getInsertedCount() !== 1) {
            throw new RuntimeException('\'Unable to create user\'');
        }
    }

}