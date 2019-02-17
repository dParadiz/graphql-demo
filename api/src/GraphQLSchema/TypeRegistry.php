<?php
declare(strict_types=1);

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
     * @var Type\ProjectMember
     */
    private static $projectMember;

    /**
     * @var Type\ProjectCategory
     */
    private static $projectCategory;
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

    /**
     * @return Type\ProjectMember
     */
    public static function projectMember(): Type\ProjectMember
    {
        if (null === self::$projectMember) {
            self::$projectMember = new Type\ProjectMember();
        }

        return self::$projectMember;
    }

    /**
     * @return Type\ProjectCategory
     */
    public static function projectCategory(): Type\ProjectCategory
    {
        if (null === self::$projectCategory) {
            self::$projectCategory = new Type\ProjectCategory();
        }

        return self::$projectCategory;
    }

}
