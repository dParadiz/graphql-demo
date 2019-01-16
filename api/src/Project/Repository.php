<?php

namespace App\Project;

use MongoDB\Client;

class Repository
{
    /**
     * @var Client
     */
    private $mongoClient;

    public function __construct(Client $client)
    {

        $this->mongoClient = $client;
    }

    public function getProjectByUserId(string $userId, array $args)
    {
        $project = new QueryModel();
        $project->id = 1;
        $project->name = 'Some name';

        return [
            $project,
        ];
    }
}
