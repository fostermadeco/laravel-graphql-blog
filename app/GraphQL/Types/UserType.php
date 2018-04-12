<?php

namespace App\GraphQL\Types;

use GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Fields\AvatarField;
use App\Models\User;

class UserType extends GraphQLType {

    protected $attributes = [
        'name'          => 'User',
        'description'   => 'A User',
        'model'         => User::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'ID of the user',
            ],
            'name' => [
                'type'          => Type::string(),
                'description'   => 'Name of the user',
            ],
            'email' => [
                'type'          => Type::string(),
                'description'   => 'Email of the user',
            ],
            'posts' => [
                'type'          =>  Type::listOf(GraphQL::type('Post')),
                'description'   => 'The posts belonging to this user',
            ],
            'avatar'             => AvatarField::class,
        ];
    }
}