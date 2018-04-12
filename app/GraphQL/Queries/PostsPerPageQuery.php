<?php

namespace App\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use App\Models\Post;

class PostsPerPageQuery extends Query  {

    protected $attributes = [
        'name' => 'postsPerPage',
        'description' => 'A paginated list of posts'
    ];

    public function authorize(array $args){
        return true;
    }

    public function type(){

        return GraphQL::paginate('Post','PostPaginator');
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
            'category_id' => [
                'sometimes',
                'numeric',
                'exists:caterories,id'
            ],
            'tag_ids.*' => [
                'numeric',
            ]
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
           'category_id' => [
                'name' => 'category_id',
                'type' => Type::int(),
            ],
            'tag_ids'   => [
                'name' => 'tag_ids',
                'type' => Type::listOf(Type::int()),
            ],
        ];
    }

    public function resolve($root , $args){

        $posts = Post::query();

        $limit = !empty($args['limit']) ? $args['limit'] : 5;
        $page = !empty($args['page']) ? $args['page'] : 1;

        if(!empty($category_id)){
            $posts = $posts->whereHas('category', function($query) use ($category_id){
                $query->where('id',$category_id);
            });
        }

        if(isset($args['tag_ids'])) {
            $tag_ids = $args['tag_ids'];
            $posts = $posts->whereHas('tags', function($query) use ($tag_ids){
                $query->whereIn('id',$tag_ids);
            });
        }

        return $posts->paginate($limit, ['*'], 'page', $page);
    }

}
