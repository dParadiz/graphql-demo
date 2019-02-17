<?php
declare(strict_types=1);

namespace App\User;

use MongoDB\Collection;
use RuntimeException;

class Create
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

    public function execute(string $id, array $document)
    {

        $exist = (bool) $this->collection->countDocuments(['id' => $id]);

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
