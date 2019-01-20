<?php

namespace App\Project;

use MongoDB\Collection;
use RuntimeException;

class Create
{
    /**
     * @var Collection
     */
    private $projectCollection;

    /**
     * @var Collection
     */
    private $userCollection;

    /**
     * CreateUser constructor.
     *
     * @param Collection $projectCollection
     */
    public function __construct(Collection $projectCollection, Collection $userCollection)
    {
        $this->projectCollection = $projectCollection;
        $this->userCollection = $userCollection;
    }

    public function execute(string $id, array $document)
    {

        $exist = (bool) $this->projectCollection->countDocuments(['id' => $id]);

        if ($exist) {
            throw new RuntimeException('Project with ' . $id . ' already exists');
        }

        $document['id'] = $id;

        $insertResult = $this->projectCollection->insertOne($document);

        if ($insertResult->getInsertedCount() !== 1) {
            throw new RuntimeException('\'Unable to create project\'');
        }
    }

}
