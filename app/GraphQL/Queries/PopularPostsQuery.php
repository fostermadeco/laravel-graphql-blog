<?php

namespace App\GraphQL\Queries;

use GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\Type;
use App\Models\Post;

class PopularPostsQuery extends Query {

    protected $attributes = [
        'name'  => 'popularPosts',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Post'));
    }

    public function args()
    {
        return [
           'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
            ],
        ];
    }

    public function rules(array $args = [])
    {
        return [
            'limit' => [
                'sometimes',
                'numeric',
                'min:1',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $limit = isset($args['limit']) ? $args['limit'] : 5;
        return Post::orderByDesc('view_count')->take($limit)->get();
    }
}