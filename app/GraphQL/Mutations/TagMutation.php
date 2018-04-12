<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use App\Models\Tag;

class TagMutation extends Mutation
{
    protected $attributes = [
        'name' => 'tag'
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
                'exists:tags,id'
            ],
            'name' => [
                'required',
                'max:50'
            ],
        ];
    }

    public function type()
    {
        return GraphQL::type('Tag');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'name' => [
                'name' => 'name',
                'type' =>  Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $tag = isset($args['id']) ? Tag::findOrFail($args['id']) : new Tag();
        $tag->fill($args);
        $tag->save();

        return $tag;
    }
}