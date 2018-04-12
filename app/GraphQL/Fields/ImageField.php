<?php

namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class ImageField extends Field {

    protected $attributes = [
        'description' => 'An Image',
    ];

    public function type(){
        return Type::string();
    }

    public function args()
    {
        return [
            'width' => [
                'type' => Type::int(),
                'description' => 'The width of the image'
            ],
            'height' => [
                'type' => Type::int(),
                'description' => 'The height of the image'
            ],
        ];
    }

    protected function resolve($root, $args)
    {   
        if (!empty($args['width']) && !empty($args['height'])) {
            return $root->imageUrl($args['width'], $args['height']);
        } else {
            return $root->imageUrl;
        }
    }
}