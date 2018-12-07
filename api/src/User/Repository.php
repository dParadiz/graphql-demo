<?php

namespace App\User;

class Repository
{

    public function getUserById(string $id): QueryModel
    {

        $user = new QueryModel();

        $user->id = 1;
        $user->email = 'some@email.com';
        $user->name = 'Test';
        $user->roles[] = 'Admin';

        return $user;
    }
}