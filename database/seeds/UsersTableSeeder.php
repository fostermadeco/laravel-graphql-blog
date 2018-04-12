<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $faker = Faker\Factory::create();

	    foreach (range(0,10) as $i) {
	        User::create([
	            'name' => $faker->name,
	            'email' => $faker->email,
	            'password' => $faker->password,
	        ]);
	    }
    }
}
