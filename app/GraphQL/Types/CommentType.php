<?php

namespace App\GraphQL\Types;

use GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Models\Comment;

class CommentType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Comment',
        'description'   => 'A Comment',
        'model'         => Comment::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The id of the comment',
            ],
            'user_id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The user id',
            ],
            'post_id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The post id',
            ],
            'body' => [
                'type'          => Type::string(),
                'description'   => 'The body of the comment',
            ],
            'user' => [
                'type'          => GraphQL::type('User'),
                'description'   => 'The user',
            ],
            'post' => [
                'type'          => GraphQL::type('Post'),
                'description'   => 'The post',
            ],
            'created_at' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The timestamp for creating the post',
                'args'  => [
                    'diffForHumans' => [
                        'type' => Type::boolean(),
                        'description' => 'Time difference for humans',
                    ]
                ]
            ],
            'updated_at' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The timestamp for updating the post',
            ],
        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return isset($args["diffForHumans"]) && $args["diffForHumans"] == true ? $root->created_at->diffForHumans() : (string)$root->created_at;
    }

    protected function resolveUpdatedAtField($root, $args)
    {
        return (string) $root->updated_at;
    }
}