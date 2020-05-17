<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

        for ($i = 0; $i < 6; $i++) {

            $checkout_date = array_rand([NULL, date('Y-m-d', strtotime('-'.mt_rand(1,21).' days') )]);
            $user_id = NULL;
            if ( $checkout_date ) {
              while ( $user_id != 1 ) {
                 $user_id = DB::table('users')->pluck('id');
              }
            }

            DB::table('titles')->insert([
                'title_id' => DB::table('titles')->pluck('id'),
                'user_id' => $user_id,
                'sn' => $faker->ean8(),
                'acquisition_date' => date('Y-m-d', strtotime('-'.mt_rand(90,7300).' days')),
                'checkout_date' => $checkout_date,
            ]);
        }

    }
}
