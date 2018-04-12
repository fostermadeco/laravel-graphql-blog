# Sample Laravel & GraphQL Blog Example

This app is a proof of concept indented to give you an idea on how to integrate GraphQL in your Laravel project, and see how it plays nicely with front-end frameworks such as Vue.js. This is a work in progress.

This package is compatible with Eloquent model (or any other data source).
* A landing page listing blog post headlines and desciptions
* A blop post page with a comments section

## Requirements:

You need to have at least PHP 7.1.3, Composer and Node.js installed on your machine

* [PHP](https://php.net/)
* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/en/)


## Installation

**1-** Simply download this package by clicking on the download button or clone it on your computer

```bash
$ git clone https://github.com/fostermadeco/laravel-graphql-blog.git
```

**2-** Go to the downloaded / cloned directory

```bash
$ cd laravel-graphql-blog
```

**3-** Create a .env file in the root directory and copy the content from .env.example

```bash
$ cp .env.example .env
```

**4-** Run Composer to install or update the new requirement.

```bash
$ composer install
```

**5-** Generate a new application key for your app

```bash
$ php artisan key:generate
```

**6-** Edit the .env file and add / update your local database credentials in the .env file 

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

```

**7-** Create a symbolink link to your public storage

```bash
$ php artisan storage:link

```

**8-** Run the database migration and seeders

```bash
$ php artisan migrate --seed

```

**9-** Run npm to install additional dependencies

```bash
$ npm install
```

**10-** Run npm to compile the Js and CSS resources

```bash
$ npm run dev
```

**11-** Launch the app

```bash
$ php artisan serve
```

**12-**
Go to http://localhost:8000/ and Voilà !!!
The GraphiQL Interface is also available at http://localhost:8000/graphql-ui


## What are we demonstrating here?

This is intended to give you a quick understanding of GraphQL
How is this sample app structured?

First, in order to build a GraphQL Server, you would need to have a GraphQL package installed in your Laravel app. In this example we are using the "rebing/graphql-laravel" package. Which comes with a configuration file located at config/graphql.php


### Schemas

Schemas are required for defining GraphQL endpoints. You can define multiple schemas and assign different **middleware** to them,
in addition to the global middleware. For example, below are a list of querie and mutations added to this app.

```
'schemas' => [
    'default' => [
        'query' => [
            'user' => App\GraphQL\Queries\UserQuery::class,
            'users' => App\GraphQL\Queries\UsersQuery::class,
            'usersPerPage' => App\GraphQL\Queries\UsersPerPageQuery::class,
			// Add more queries here
        ],
        'mutation' => [
            'createUser' => App\GraphQL\Mutations\CreateUserMutation::class,
			// Add more mutations here
        ],
        'middleware' => []
    ],
]
```

### How to Create a New GraphQL Query?

First you need to create a type. The Eloquent Model is only required, if specifying relations.

**NB! The `selectable` key is required, if it's a non-database field or not a relation**

```php
	namespace App\GraphQL\Type;
	
	use GraphQL\Type\Definition\Type;
	use Rebing\GraphQL\Support\Type as GraphQLType;
    
    class UserType extends GraphQLType {
        
        protected $attributes = [
            'name'          => 'User',
            'description'   => 'A user',
            'model'         => UserModel::class,
        ];
		
        public function fields()
        {
            return [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'The id of the user',
                    'alias' => 'user_id', // Use 'alias', if the database column is different from the type name
                ],
                'email' => [
                    'type' => Type::string(),
                    'description' => 'The email of user',
                ],
                // Uses the 'getIsMeAttribute' function on our custom User model
                'isMe' => [
                    'type' => Type::boolean(),
                    'description' => 'True, if the queried user is the current user',
                    'selectable' => false, // Does not try to query this from the database
                ]
            ];
        }
            
            
        // If you want to resolve the field yourself, you can declare a method
        // with the following format resolve[FIELD_NAME]Field()
        protected function resolveEmailField($root, $args)
        {
            return strtolower($root->email);
        }
        
    }

```

Add the type to the `config/graphql.php` configuration file

```php
	'types' => [
		'user' => 'App\GraphQL\Type\UserType'
	]

```

You could also add the type with the `GraphQL` Facade, in a service provider for example.

```php
	GraphQL::addType('App\GraphQL\Type\UserType', 'user');

```

Then you need to define a query that returns this type (or a list). You can also specify arguments that you can use in the resolve method.
```php
	namespace App\GraphQL\Query;
	
	use GraphQL;
	use GraphQL\Type\Definition\Type;
	use Rebing\GraphQL\Support\Query;    
	use App\User;
	
	class UsersQuery extends Query {
	
		protected $attributes = [
			'name' => 'Users query'
		];
		
		public function type()
		{
			return Type::listOf(GraphQL::type('user'));
		}
		
		public function args()
		{
			return [
				'id' => ['name' => 'id', 'type' => Type::string()],
				'email' => ['name' => 'email', 'type' => Type::string()]
			];
		}
		
		public function resolve($root, $args)
		{
			if(isset($args['id']))
			{
				return User::where('id' , $args['id'])->get();
			}
			else if(isset($args['email']))
			{
				return User::where('email', $args['email'])->get();
			}
			else
			{
				return User::all();
			}
		}
	
	}

```

Add the query to the `config/graphql.php` configuration file

```php
    'schemas' => [
		'default' => [
		    'query' => [
                'users' => 'App\GraphQL\Query\UsersQuery'
            ],
            // ...
		]
	]
```

And that's it. You should be able to query GraphQL with a request to the url `/graphql` (or anything you choose in your config). Try a GET request with the following `query` input

```
    query FetchUsers {
        users {
            id
            email
        }
    }
```

For example, if you use homestead:
```
http://homestead.app/graphql?query=query+FetchUsers{users{id,email}}
```

### Creating a mutation

A mutation is like any other query, it accepts arguments (which will be used to do the mutation) and returns an object of a certain type.

For example a mutation to update the password of a user. First you need to define the Mutation.

```php
	namespace App\GraphQL\Mutation;
	
	use GraphQL;
	use GraphQL\Type\Definition\Type;
	use Rebing\GraphQL\Support\Mutation;    
	use App\User;
	
	class UpdateUserPasswordMutation extends Mutation {
	
		protected $attributes = [
			'name' => 'UpdateUserPassword'
		];
		
		public function type()
		{
			return GraphQL::type('user');
		}
		
		public function args()
		{
			return [
				'id' => ['name' => 'id', 'type' => Type::nonNull(Type::string())],
				'password' => ['name' => 'password', 'type' => Type::nonNull(Type::string())]
			];
		}
		
		public function resolve($root, $args)
		{
			$user = User::find($args['id']);
			if(!$user)
			{
				return null;
			}
			
			$user->password = bcrypt($args['password']);
			$user->save();
			
			return $user;
		}
	
	}

```

As you can see in the `resolve` method, you use the arguments to update your model and return it.

You then add the muation to the `config/graphql.php` configuration file

```php
    'schemas' => [
		'default' => [
		    'mutation' => [
                'updateUserPassword' => 'App\GraphQL\Mutation\UpdateUserPasswordMutation'
            ],
            // ...
		]
	]
```

You should then be able to use the following query on your endpoint to do the mutation.

```
    mutation users {
        updateUserPassword(id: "1", password: "newpassword") {
            id
            email
        }
    }
```

if you use homestead:
```
http://homestead.app/graphql?query=mutation+users{updateUserPassword(id: "1", password: "newpassword"){id,email}}
```

#### Adding validation to queries or mutation

It is possible to add validation rules to queries or mutation. It uses the laravel `Validator` to performs validation against the `args`.

When creating a mutation, you can add a method to define the validation rules that apply by doing the following:

```php
	namespace App\GraphQL\Mutation;
	
	use GraphQL;
	use GraphQL\Type\Definition\Type;
	use Rebing\GraphQL\Support\Mutation;    
	use App\User;
	
	class UpdateUserEmailMutation extends Mutation {
	
		protected $attributes = [
			'name' => 'UpdateUserEmail'
		];
		
		public function type()
		{
			return GraphQL::type('user');
		}
		
		public function args()
		{
			return [
				'id' => ['name' => 'id', 'type' => Type::string()],
				'email' => ['name' => 'password', 'type' => Type::string()]
			];
		}
		
		public function rules(array $args)
		{
			return [
				'id' => ['required'],
				'email' => ['required', 'email']
			];
		}
		
		public function resolve($root, $args)
		{
			$user = User::find($args['id']);
			if(!$user)
			{
				return null;
			}
			
			$user->email = $args['email'];
			$user->save();
			
			return $user;
		}
	
	}

```

Alternatively you can define rules with each args

```php
	
	class UpdateUserEmailMutation extends Mutation {
	
		//...
		
		public function args()
		{
			return [
				'id' => [
					'name' => 'id',
					'type' => Type::string(),
					'rules' => ['required']
				],
				'email' => [
					'name' => 'email',
					'type' => Type::string(),
					'rules' => ['required', 'email']
				]
			];
		}
		
		//...
	
	}

```

When you execute a mutation, it will return the validation errors. Since GraphQL specifications define a certain format for errors, the validation errors messages are added to the error object as a extra `validation` attribute. To find the validation error, you should check for the error with a `message` equals to `'validation'`, then the `validation` attribute will contain the normal errors messages returned by the Laravel Validator.

```json
	{
		"data": {
			"updateUserEmail": null
		},
		"errors": [
			{
				"message": "validation",
				"locations": [
					{
						"line": 1,
						"column": 20
					}
				],
				"validation": {
					"email": [
						"The email is invalid."
					]
				}
			}
		]
	}
```
