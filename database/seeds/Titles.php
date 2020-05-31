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
        // hard-code this ISBN
        self::add('War and Peace', '1234567890');

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 12; $i++) {
            self::add(
                ucwords($faker->sentence($nbWords = rand(1,6), $variableNbWords = true)),
                $faker->isbn13()
            );
        }

    }

    private function add($title, $isbn) {
        DB::table('titles')->insert([
            'title' => $title,
            'isbn' => $isbn,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
