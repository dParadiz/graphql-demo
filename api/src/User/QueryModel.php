<?php

namespace App\User;

class QueryModel
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $email;

    /**
     * @var string[]
     */
    public $roles = [];
}
