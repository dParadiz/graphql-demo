<?php

namespace App\Project;

use RuntimeException;

class Delete
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

    public function execute($id)
    {
        $deleteResult = $this->collection->deleteOne(['id' => $id]);

        if ($deleteResult->getDeletedCount() !== 1) {
            throw new RuntimeException('No project with ' . $id . ' was removed');
        }
    }
}