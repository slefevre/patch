<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Titles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 12; $i++) {
            DB::table('titles')->insert([
                'title' => ucwords($faker->sentence($nbWords = rand(1,6), $variableNbWords = true)),
                'isbn' => $faker->isbn13(),
                'media' => array_rand(['book','ebook','dvd','cd']),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

    }
}
