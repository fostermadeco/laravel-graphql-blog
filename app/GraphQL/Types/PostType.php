<?php

namespace App\GraphQL\Types;

use GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Models\Post;
use App\GraphQL\Fields\ImageField;

class PostType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Post',
        'description'   => 'A Post',
        'model'         => Post::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The id of the post',
            ],
            'category_id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The id of the category',
            ],
            'user_id' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The id of the user',
            ],
            'slug' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The slug of the user',
            ],
            'title' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The title of the post',
            ],
            'description' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The description of the post',
            ],
            'body' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The body of the post',
            ],
            'user' => [
                'type'          => GraphQL::type('User'),
                'description'   => 'The user of the post',
            ],
            'category' => [
                'type'          => GraphQL::type('Category'),
                'description'   => 'The category of the post',
            ],
            'related_posts' => [
                'type'          => Type::listOf(GraphQL::type('Post')),
                'description'   => 'The related posts',
                'args'  => [
                    'limit' => [
                        'type'          => Type::int(),
                        'description'   => 'The number of related posts',
                    ]
                ],
                'selectable' => false
            ],
            'comments' => [
                'type'          => Type::listOf(GraphQL::type('Comment')),
                'description'   => 'The comments of the post',
            ],
            'comment_count' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The number of comments',
                'selectable'    => false,
            ],
            'view_count' => [
                'type'          => Type::nonNull(Type::int()),
                'description'   => 'The number of views',
            ],
            'tags' => [
                'type'          => Type::listOf(GraphQL::type('Tag')),
                'description'   => 'The tags of the post',
            ],
            'created_at' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The timestamp for creating the post',
            ],
            'updated_at' => [
                'type'          => Type::nonNull(Type::string()),
                'description'   => 'The timestamp for updating the post',
            ],
            'image'             => ImageField::class,
        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return (string) $root->created_at;
    }

    protected function resolveUpdatedAtField($root, $args)
    {
        return (string) $root->updated_at;
    }

    protected function resolveCommentCountField($root, $args)
    {
        return $root->comments->count();
    }

    protected function resolveRelatedPostsField($root, $args)
    {
        return  (isset($args['limit'])) ? $root->relatedPosts->take($args['limit']) : $root->relatedPosts;
    }
}