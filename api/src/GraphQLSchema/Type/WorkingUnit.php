<?php
declare(strict_types=1);

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\{ObjectType, Type};

class WorkingUnit extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'WorkingUnit',
            'description' => 'Working unit',
            'fields' => function () {
                return [
                    'id' => Type::string(),
                    'email' => Type::string(),

                ];
            },
        ];

        parent::__construct($config);
    }

}
