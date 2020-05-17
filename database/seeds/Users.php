<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // librarian is user id 1
        User::create([
            'name' => 'librarian',
            'email' => 'librarian@test.com',
            'password' => Hash::make(Str::random(20)),
        ]);

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make(Str::random(20)),
            ]);
        }

    }
}
