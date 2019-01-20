<?php

namespace App\Project;

use RuntimeException;

class Update
{
    /**
     * @var \MongoDB\Collection
     */
    private $projectCollection;
    /**
     * @var \MongoDB\Collection
     */
    private $userCollection;

    /**
     * CreateUser constructor.
     *
     * @param \MongoDB\Collection $collection
     */
    public function __construct(\MongoDB\Collection $projectCollection, \MongoDB\Collection $userCollection)
    {
        $this->projectCollection = $projectCollection;
        $this->userCollection = $userCollection;
    }

    public function execute(string $id, array $document)
    {
        $exists = (bool) $this->projectCollection->countDocuments(['id' => $id]);

        if (!$exists) {
            throw new RuntimeException('Project with ' . $id . ' does not exists');
        }

        unset($document['id']);

        $updateResult = $this->projectCollection->updateOne(['id' => $id], ['$set' => $document]);

        if ($updateResult->getModifiedCount() !== 1) {
            throw new RuntimeException('No project records were updated');
        }
    }
}
