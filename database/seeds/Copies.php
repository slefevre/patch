<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Copies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {

            // make the book checked out 2/3rds of the time
            $user_id = NULL;
            $date = NULL;
            if ( rand(1,10) % 3 > 1 ) {
              $date = date('Y-m-d', strtotime('-'.mt_rand(1,21).' days') );
              while ( $user_id === NULL || $user_id == 1 ) {
                 $user_id = DB::table('users')->inRandomOrder()->first()->id;
              }
            }

            DB::table('copies')->insert([
                'title_id' => DB::table('titles')->inRandomOrder()->first()->id,
                'checkout_user_id' => $user_id,
                'sn' => $faker->ean8(),
                'acquisition_date' => date('Y-m-d', strtotime('-'.mt_rand(90,7300).' days')),
                'checkout_date' => $date,
                'damage_notes' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

    }
}
