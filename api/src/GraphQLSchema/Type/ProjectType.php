<?php
declare(strict_types=1);

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\{ObjectType, Type};

class ProjectType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Project',
            'description' => 'Project',
            'fields' => function () {
                return [
                    'id' => Type::string(),
                    'name' => Type::string(),
                    'description' => Type::string(),

                ];
            },
        ];

        parent::__construct($config);
    }

}
