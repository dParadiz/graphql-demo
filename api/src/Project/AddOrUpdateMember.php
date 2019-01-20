<?php

namespace App\Project;

use RuntimeException;

class AddOrUpdateMember
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

    public function execute(string $id, array $newMember)
    {
        $project = $this->projectCollection->findOne(['id' => $id]);

        if (null === $project) {
            throw new RuntimeException('No project with ' . $id . ' found');
        }

        $memberExists = (bool)$this->userCollection->countDocuments(['id' => $newMember['id']]);

        if (!$memberExists) {
            throw new RuntimeException('No user with ' . $newMember['id'] . ' found');
        }

        $project = $project->getArrayCopy();

        $members = [];
        if (isset($project['members'])) {
            $members = $project['members']->getArrayCopy();
        }
        $project['members'] = array_filter($members, function($member) use ($newMember) {
            return $member['id'] === $newMember['id'];
        });

        $project['members'][] = $newMember;


        $updateResult = $this->projectCollection->updateOne(['id' => $id], ['$set' => $project]);

        if ($updateResult->getModifiedCount() !== 1) {
            throw new RuntimeException('No project records were updated');
        }
    }
}