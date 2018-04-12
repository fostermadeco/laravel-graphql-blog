<?php

namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\Models\Tag;

class TagQuery extends Query {

    protected $attributes = [
        'name'  => 'tag',
    ];

    public function type()
    {
        return GraphQL::type('Tag');
    }

    public function rules(array $args = [])
    {
        return [
            'id' => [
                'required',
                'numeric',
                'exists:tags,id'
            ],
        ];
    }

    public function args()
    {
        return [
            'id'    => [
                'name' => 'id',
                'type' => Type::int(),
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return Tag::where('id', '=', $args['id'])->with($with)->select($select)->firstOrFail();
    }
}