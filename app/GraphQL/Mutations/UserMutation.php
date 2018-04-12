<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'user'
    ];

    public function authorize(array $args = [])
    {
        return true;
    }

    public function rules(array $args = [])
    {
        $rules = [
            'id' => [
                'sometimes',
                'numeric',
                'exists:users,id'
            ],
            'name' => [
                'sometimes',
                'max:50'
            ],
            'email' => [
                'sometimes',
                'email',
                isset($args['id']) ? Rule::unique('users')->ignore($args['id']) : 'unique:users,email',
            ],
        ];

        if (auth()->check()) {
            $rules += [
                'password' => [
                    'sometimes',
                    'min:5',
                ]
            ];
        }

        return $rules;
    }

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        $args = [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'name' => [
                'name' => 'name',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'email' => [
                'name' => 'email',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'password' => [
                'name' => 'password',
                'type' =>  Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = isset($args['id']) ? User::findOrFail($args['id']) : new User();
        $user->fill($args);
        $user->save();

        return $user;
    }
}