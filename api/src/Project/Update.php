<?php
declare(strict_types=1);

namespace App\Project;

use MongoDB\Collection;
use RuntimeException;

class Update
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
     * @param Collection $userCollection
     */
    public function __construct(Collection $projectCollection, Collection $userCollection)
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
