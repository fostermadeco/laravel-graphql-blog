<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    $faker = Faker\Factory::create();
	    $users = User::select('id')->get();
	    $categories = Category::select('id')->get();
	    $tags = Tag::all();

   		$images = File::allFiles(storage_path('app/public/images'));
        
	    foreach (range(0,10) as $i) {

	        Post::create([
	            'title' => $faker->sentence,
	            'description' => $faker->sentence,
	            'body' => $faker->paragraph(10),
	            'user_id' => $users->random()->id,
	            'category_id' => $categories->random()->id,
	            'image' => $images[$i]->getFilename(),
	            'view_count' => rand(1,100),
	        ])
	        ->tags()
	        ->sync($tags->random(rand(1,3))->pluck('id')->toArray());
	    }
    }
}
