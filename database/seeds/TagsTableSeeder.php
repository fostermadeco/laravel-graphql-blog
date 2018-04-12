<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$tagNames = [
			'Web Development',
			'Laravel',
			'JavaScript',
			'Backend Development',
			'API',
			'Tech',
			'React Native',
			'React',
			'Framework',
			'Vuejs'
		];

	    foreach ($tagNames as $name) {
	        Tag::create([
	        	'name' => $name,
	        ]);
	    }
	}
}
