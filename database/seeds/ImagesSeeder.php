<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        File::cleanDirectory(storage_path('app/public/images/'));

	    $faker = Faker\Factory::create();

	    foreach (range(0,10) as $i) {
	    	$faker->image(storage_path('app/public/images/'), $width = 1024, $height = 512);
	    }
    }
}
