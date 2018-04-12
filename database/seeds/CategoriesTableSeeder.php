<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$categoryNames = [
            'News',
            'Features',
            'Videos'
        ];

	    foreach ($categoryNames as $name) {
	        Category::create([
	        	'name' => $name,
	        ]);
	    }
    }
}
