<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class UserLoginMutation extends Mutation {

    protected $attributes = [
        'name'          => 'userLogin',
        'description'   => 'User Login using credentials',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function rules(array $args = [])
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email'
            ],
            'password' => [
                'required',
            ],
        ];
    }

    public function args()
    {
        return [
            'email' => [
                'name'  => 'email',
                'type'  => Type::nonNull(Type::string()),
            ],
            'password' => [
                'name'  => 'password',
                'type'  => Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = null;

        if ($token = JWTAuth::attempt($args)) {
            $user = auth()->user();
            $user->token = $token;
        } else {
            abort(401,"Unauthorized");
        }

        return $user;
    }
}