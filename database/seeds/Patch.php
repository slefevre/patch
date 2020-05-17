<?php

use Illuminate\Database\Seeder;

class Patch extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            USerSeeder::class,
            TitlesSeeder::class,
            CopiesSeeder::class,
        ]);
    }
}
