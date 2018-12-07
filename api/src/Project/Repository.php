<?php

namespace App\Project;

class Repository
{

    public function getProjectByUserId(string $userId, array $args)
    {
        $project = new QueryModel();
        $project->id = 1;
        $project->name = 'Some name';

        return [
            $project
        ];
    }
}