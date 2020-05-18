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
        DB::table('users')->insert([
            'name' => 'librarian',
            'email' => 'librarian@test.com',
            'password' => Hash::make(Str::random(20)),
        ]);

        for ($i = 0; $i < 6; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make(Str::random(20)),
            ]);
        }

    }
}
