<?php

namespace App\GraphQL\Types;

use GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Models\Tag;

class TagType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Tag',
        'description'   => 'A Tag',
        'model'         => Tag::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'ID of the tag',
            ],
            'name' => [
                'type'          => Type::string(),
                'description'   => 'Name of the tag',
            ],
        ];
    }

}