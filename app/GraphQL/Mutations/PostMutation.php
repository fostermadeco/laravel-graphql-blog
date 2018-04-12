<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use App\Models\Post;

class PostMutation extends Mutation
{
    protected $attributes = [
        'name' => 'post'
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
                'exists:posts,id'
            ],
            'title' => [
                'required',
                'max:50'
            ],
            'category_id' => [
                'required',
                'numeric',
                'exists:categories,id'
            ],
            'body' => [
                'required',
                'max:1000'
            ]
        ];
    }

    public function type()
    {
        return GraphQL::type('Post');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'category_id' => [
                'name' => 'category_id',
                'type' => Type::nonNull(Type::int())
            ],
            'title' => [
                'name' => 'title',
                'type' =>  Type::nonNull(Type::string())
            ],
            'body' => [
                'name' => 'body',
                'type' =>  Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $post = isset($args['id']) ? Post::findOrFail($args['id']) : new Post();
        $post->fill($args);
        $post->save();

        return $post;
    }
}