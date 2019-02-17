<?php
declare(strict_types=1);

namespace App\GraphQLSchema\Type;

use GraphQL\Type\Definition\{ObjectType, Type};

class MutationResponse extends ObjectType
{
    /** @var string */
    public $status;
    /** @var string */
    public $message;

    public function __construct(string $status = '', string $message = '')
    {
        $config = [
            'name' => 'MutationResponse',
            'fields' => function () {
                return [
                    'status' => Type::string(),
                    'message' => Type::string(),
                ];
            },
        ];

        parent::__construct($config);

        $this->status = $status;
        $this->message = $message;
    }

}
