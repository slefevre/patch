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
        self::add(3, date('Y-m-d', strtotime('-16 days')), 1122334455);
        self::add(3, date('Y-m-d', strtotime('-18 days')), 1112223334);
        self::add(3, date('Y-m-d', strtotime('-30 days')), 2221113334);

        // hardcode an SN for checking out.
        self::add(NULL,NULL, 1234567810);

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
