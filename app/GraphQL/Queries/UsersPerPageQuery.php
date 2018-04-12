<?php

namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use App\Models\User;

class UsersPerPageQuery extends Query  {

    protected $attributes = [
        'name' => 'usersPerPage',
        'description' => 'A paginated list of users'
    ];

    public function authorize(array $args){
        return true;
    }

    public function type(){

        return GraphQL::paginate('User','UserPaginator');
    }

    public function rules(array $args = [])
    {
        return [
            'limit' => [
                'sometimes',
                'numeric',
                'min:1',
            ],
            'page' => [
                'sometimes',
                'numeric',
                'min:1',
            ],
        ];
    }

    public function args(){
        return [
           'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
            ],
           'page' => [
                'name' => 'page',
                'type' => Type::int(),
            ],
        ];
    }

    public function resolve($root , $args){

        $users = User::query();

        $limit = !empty($args['limit']) ? $args['limit'] : 5;
        $page = !empty($args['page']) ? $args['page'] : 1;

        return $users->paginate($limit, ['*'], 'page', $page);
    }

}
