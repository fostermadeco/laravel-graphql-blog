<?php

namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\Models\User;

class UserQuery extends Query {

    protected $attributes = [
        'name'  => 'user',
    ];

    public function authorize(array $args = [])
    {
        return true;
    }

    public function type()
    {
        return GraphQL::type('User');
    }

    public function rules(array $args = [])
    {
        return [
            'id' => [
                'required',
                'numeric',
                'min:1',
                'exists:users,id'
            ],
        ];
    }

    public function args()
    {
        return [
            'id'    => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => [
                    'required',
                    'numeric',
                    'min:1',
                    'exists:users,id'
                ]
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return User::where('id', '=', $args['id'])->with($with)->select($select)->firstOrFail();
    }

}