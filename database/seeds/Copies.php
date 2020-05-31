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

        // user 2 has three copies checked out
        for ($i = 0; $i < 3; $i++) {
            $date = date('Y-m-d', strtotime('-'.mt_rand(1,13).' days') );
            self::add(2, $date, 1234567890 + $i);
        }

        // user 3 has an overdue book
        $date = date('Y-m-d', strtotime('-16 days') );
        self::add(3, $date, 1234567899 );

        // user 4 has three overdue copies checked out
        for ($i = 0; $i < 3; $i++) {
            $date = date('Y-m-d', strtotime('-'.mt_rand(14,28).' days') );
            self::add(4, $date, 1234567895 + $i);
        }

        $faker = \Faker\Factory::create();

        // 30 random copies and checkouts
        for ($i = 0; $i < 15; $i++) {

            // make the book checked out 2/3rds of the time
            $user_id = NULL;
            $date = NULL;
            if ( rand(1,10) % 3 > 1 ) {
                 $date = date('Y-m-d', strtotime('-'.mt_rand(1,21).' days') );
                 while ( $user_id === NULL || $user_id < 5 ) {
                     $user_id = DB::table('users')->inRandomOrder()->first()->id;
                 }
            }
#            self::add($user_id, $date, $faker->ean8());
        }
    }

    public function add($user_id, $date, $sn) {
        DB::table('copies')->insert([
            'title_id' => DB::table('titles')->inRandomOrder()->first()->id,
            'checkout_user_id' => $user_id,
            'sn' => $sn,
            'acquisition_date' => date('Y-m-d', strtotime('-'.mt_rand(90,7300).' days')),
            'checkout_date' => $date,
            'damage_notes' => NULL,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

}
