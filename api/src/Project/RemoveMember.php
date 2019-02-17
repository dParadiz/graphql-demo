<?php
declare(strict_types=1);

namespace App\Project;

use MongoDB\Collection;
use RuntimeException;

class RemoveMember
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
    public function __construct(Collection $projectCollection)
    {
        $this->projectCollection = $projectCollection;
    }

    public function execute(string $id, array $newMember)
    {
        $project = $this->projectCollection->findOne(['id' => $id]);

        if (null === $project) {
            throw new RuntimeException('No project with ' . $id . ' found');
        }

        $project = $project->getArrayCopy();

        $members = [];
        if (isset($project['members'])) {
            $members = $project['members']->getArrayCopy();
        }

        $project['members'] = array_filter($members, function ($member) use ($newMember) {
            return $member['id'] !== $newMember['id'];
        });

        $updateResult = $this->projectCollection->updateOne(['id' => $id], ['$set' => $project]);

        if ($updateResult->getModifiedCount() !== 1) {
            throw new RuntimeException('No project records were updated');
        }
    }
}
