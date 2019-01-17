<?php

namespace App\GraphQLSchema;

class TypeRegistry
{
    /**
     * @var Type\UserType
     */
    private static $user;

    /**
     * @var Type\ProjectType
     */
    private static $project;

    /**
     * @var Type\WorkingUnit
     */
    private static $workingUnit;

    /**
     * @var Type\MutationResponse
     */
    private static $mutationResponse;

    /**
     * @return Type\UserType
     */
    public static function user(): Type\UserType
    {
        if (null === self::$user) {
            self::$user = new Type\UserType();
        }

        return self::$user;
    }

    /**
     * @return Type\ProjectType
     */
    public static function project(): Type\ProjectType
    {
        if (null === self::$project) {
            self::$project = new Type\ProjectType();
        }

        return self::$project;
    }

    /**
     * @return Type\WorkingUnit
     */
    public static function workingUnit(): Type\WorkingUnit
    {
        if (null === self::$workingUnit) {
            self::$workingUnit = new Type\WorkingUnit();
        }

        return self::$workingUnit;
    }

    /**
     * @return Type\MutationResponse
     */
    public static function mutationResponse(): Type\MutationResponse
    {
        if (null === self::$mutationResponse) {
            self::$mutationResponse = new Type\MutationResponse();
        }

        return self::$mutationResponse;
    }

}
