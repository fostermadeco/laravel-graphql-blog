<?php

namespace App\GraphQL\Queries;

use GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\Type;
use App\Models\Comment;

class CommentsQuery extends Query {

    protected $attributes = [
        'name'  => 'comments',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Comment'));
    }

    public function args()
    {
        return [
            'post_id'   => [
                'name' => 'post_id',
                'type' => Type::int(),
            ],
        ];
    }

    public function rules(array $args = [])
    {
        return [
            'post_id' => [
                'numeric',
                'exists:posts,id'
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if (isset($args['post_id'])) {
            return Comment::where('post_id',$args['post_id'])->get();
        }

        return Comment::all();
    }
}