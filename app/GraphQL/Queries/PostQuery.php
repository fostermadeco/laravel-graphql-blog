<?php

namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\Models\Post;

class PostQuery extends Query {

    protected $attributes = [
        'name'  => 'post',
    ];

    public function type()
    {
        return GraphQL::type('Post');
    }

    public function rules(array $args = [])
    {
        return [
            'id' => [
                'required',
                'numeric',
                'exists:posts,id'
            ],
        ];
    }

    public function args()
    {
        return [
            'id'    => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return Post::where('id', '=', $args['id'])->with($with)->select($select)->firstOrFail();
    }
}