<?php

namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class AvatarField extends Field {

    protected $attributes = [
        'description' => 'Avatar (picture) of the user',
        'selectable' => false,
    ];

    public function type(){
        return Type::string();
    }

    public function args()
    {
        return [
            'size' => [
                'type' => Type::int(),
                'description' => 'The size of the avatar',
            ]
        ];
    }

    protected function resolve($root, $args)
    {
        return isset($args['size']) ? $root->avatar($args['size']) : $root->avatar;
    }
}