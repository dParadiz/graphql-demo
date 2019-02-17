<?php
declare(strict_types=1);

namespace App\Project;

use MongoDB\Collection;
use RuntimeException;

class Delete
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * CreateUser constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
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
