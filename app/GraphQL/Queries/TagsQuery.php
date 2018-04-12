<?php

namespace App\GraphQL\Queries;

use GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\Type;
use App\Models\Tag;

class TagsQuery extends Query {

    protected $attributes = [
        'name'  => 'tags',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Tag'));
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
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if (isset($args['ids'])) {
            return Tag::whereIn('id',$args['ids'])->with($with)->select($select)->get();
        }

        return Tag::all();
    }
}