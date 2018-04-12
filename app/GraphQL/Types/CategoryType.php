<?php

namespace App\GraphQL\Types;

use GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Models\Category;

class CategoryType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Category',
        'description'   => 'A Category',
        'model'         => Category::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'ID of the category',
            ],
            'name' => [
                'type'          => Type::string(),
                'description'   => 'Name of the category',
            ],
            'posts' => [
                'type'          => Type::listOf(GraphQL::type('Post')),
                'description'   => 'The posts of the category',
            ],
        ];
    }

}