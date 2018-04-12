<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use App\Models\Comment;

class CommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'comment'
    ];

    public function authorize(array $args = [])
    {
        return true;
    }

    public function rules(array $args = [])
    {
        return [
            'id' => [
                'sometimes',
                'numeric',
                'exists:comments,id'
            ],
            'body' => [
                'required',
                'max:1000'
            ]
        ];
    }

    public function type()
    {
        return GraphQL::type('Comment');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'body' => [
                'name' => 'body',
                'type' =>  Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $comment = isset($args['id']) ? Comment::findOrFail($args['id']) : new Comment();
        $comment->fill($args);

        //associate the currently logged in user

        $comment->save();

        return $comment;
    }
}