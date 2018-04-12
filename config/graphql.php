<?php


use example\Mutation\ExampleMutation;
use example\Query\ExampleQuery;
use example\Type\ExampleRelationType;
use example\Type\ExampleType;

return [

    // The prefix for routes
    'prefix' => 'api/graphql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{graphql_schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',

    // Any middleware for the graphql route group
    'middleware' => ['api'],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                'user' => App\GraphQL\Queries\UserQuery::class,
                'users' => App\GraphQL\Queries\UsersQuery::class,
                'usersPerPage' => App\GraphQL\Queries\UsersPerPageQuery::class,
                'post' => App\GraphQL\Queries\PostQuery::class,
                'posts' => App\GraphQL\Queries\PostsQuery::class,
                'popularPosts' => App\GraphQL\Queries\PopularPostsQuery::class,
                'postsPerPage' => App\GraphQL\Queries\PostsPerPageQuery::class,
                'tag' => App\GraphQL\Queries\TagQuery::class,
                'tags' => App\GraphQL\Queries\TagsQuery::class,
                'category' => App\GraphQL\Queries\CategoryQuery::class,
                'categories' => App\GraphQL\Queries\CategoriesQuery::class,
                'comment' => App\GraphQL\Queries\CommentQuery::class,
                'comments' => App\GraphQL\Queries\CommentsQuery::class,
            ],
            'mutation' => [
                'user' => App\GraphQL\Mutations\UserMutation::class,
                'createUser' => App\GraphQL\Mutations\CreateUserMutation::class,
                'userLogin' => App\GraphQL\Mutations\UserLoginMutation::class,
                'post' => App\GraphQL\Mutations\PostMutation::class,
                'tag' => App\GraphQL\Mutations\TagMutation::class,
                'category' => App\GraphQL\Mutations\CategoryMutation::class,
                'comment' => App\GraphQL\Mutations\CommentMutation::class,
                'commentAsGuest' => App\GraphQL\Mutations\CommentAsGuestMutation::class,
            ],
            'middleware' => []
        ],
    ],
    
    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        'User' => App\GraphQL\Types\UserType::class,
        'Post' => App\GraphQL\Types\PostType::class,
        'Tag' => App\GraphQL\Types\TagType::class,
        'Category' => App\GraphQL\Types\CategoryType::class,
        'Comment' => App\GraphQL\Types\CommentType::class,
    ],
    
    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    //'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],
    'error_formatter' => ['App\GraphQL\Formatters\ErrorFormatter', 'formatError'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key'    => 'variables',
    
];
