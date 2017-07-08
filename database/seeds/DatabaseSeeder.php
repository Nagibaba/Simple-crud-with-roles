<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  //       App\User::truncate();
		// factory(App\User::class, 100)->create();    	
        // $this->call(UsersTableSeeder::class);
        factory(App\Post::class, 200)->create();        
    }
}
