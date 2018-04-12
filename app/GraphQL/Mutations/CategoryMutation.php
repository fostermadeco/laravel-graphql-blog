<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'category'
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
                'exists:categories,id'
            ],
            'name' => [
                'required',
                'max:50'
            ],
        ];
    }

    public function type()
    {
        return GraphQL::type('Category');
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
        $category = isset($args['id']) ? Category::findOrFail($args['id']) : new Category();
        $category->fill($args);
        $category->save();

        return $category;
    }
}