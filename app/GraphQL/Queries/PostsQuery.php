<?php

namespace App\GraphQL\Queries;

use GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\Type;
use App\Models\Post;

class PostsQuery extends Query {

    protected $attributes = [
        'name'  => 'posts',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Post'));
    }

    public function args()
    {
        return [
            'ids'   => [
                'name' => 'ids',
                'type' => Type::listOf(Type::int()),
            ],
        ];
    }

    public function rules(array $args = [])
    {
        return [
            'ids' => [
                'array',
            ],
            'ids.*' => [
                'numeric',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $posts = Post::query();

        if (isset($args['ids'])) {
            $posts = $posts->whereIn('id',$args['ids'])->with($with)->select($select);
        }

        return $posts->get();
    }
}