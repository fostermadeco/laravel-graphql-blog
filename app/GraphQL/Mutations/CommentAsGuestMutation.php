<?php

namespace App\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Validation\Rule;
use GraphQL\Type\Definition\InputObjectType;
use App\Models\User;
use App\Models\Comment;

class CommentAsGuestMutation extends Mutation
{
    protected $attributes = [
        'name' => 'commentAsGuest'
    ];

    public function authorize(array $args = [])
    {
        return true;
    }

    public function rules(array $args = [])
    {
        return [
            'post_id' => [
                'sometimes',
                'numeric',
                'exists:posts,id'
            ],
            'body' => [
                'required',
                'max:1000'
            ],
            'user.email' => [
                'required',
                'email',
            ],
            'user.name' => [
                'required',
                'string',
            ],
        ];
    }

    public function type()
    {
        return GraphQL::type('Comment');
    }

    public function args()
    {
        return [
            'post_id' => [
                'name' => 'post_id',
                'type' => Type::nonNull(Type::int())
            ],
            'body' => [
                'name' => 'body',
                'type' =>  Type::nonNull(Type::string())
            ],
            'user' => [
                'name' => 'user',
                'type' =>Type::nonNull( new InputObjectType([
                    'name' => 'CommentUserInput',
                    'fields' =>  [
                        'name' => [
                            'name' => 'name',
                            'type' => Type::string()
                        ],
                        'email' => [
                            'name' => 'email',
                            'type' => Type::string()
                        ],
                    ]
                ])),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $userData = $args['user'];
        $user = User::where('email',$userData['email'])->first();

        if (!$user) {
            $user = new User(['name' => $userData['name'], 'email' => $userData['email']]);
            $user->save();
        }

        $comment = new Comment(['post_id' => $args['post_id'], 'user_id' => $user->id, 'body' => $args['body'] ]);
        $comment->save();

        return $comment;
    }
}