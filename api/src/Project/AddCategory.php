<?php
declare(strict_types=1);

namespace App\Project;

use RuntimeException;
use MongoDB\Collection;

class AddCategory
{
    /**
     * @var Collection
     */
    private $projectCollection;

    /**
     * CreateUser constructor.
     *
     * @param Collection $projectCollection
     */
    public function __construct(Collection $projectCollection)
    {
        $this->projectCollection = $projectCollection;
    }

    public function execute(string $id, array $newCategory)
    {
        $project = $this->projectCollection->findOne(['id' => $id]);

        if (null === $project) {
            throw new RuntimeException('No project with ' . $id . ' found');
        }

        $project = $project->getArrayCopy();

        $categories = [];

        if (isset($project['categories'])) {
            $categories = $project['categories']->getArrayCopy();
        }

        $project['categories'] = array_filter($categories, function ($category) use ($newCategory) {
            return $category['id'] !== $newCategory['id'];
        });

        $project['categories'][] = $newCategory;

        $updateResult = $this->projectCollection->updateOne(['id' => $id], ['$set' => $project]);

        if ($updateResult->getModifiedCount() !== 1) {
            throw new RuntimeException('No project records were updated');
        }
    }
}
