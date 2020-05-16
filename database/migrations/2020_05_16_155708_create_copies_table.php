<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('title_id');
            $table->foreign('title_id')->references('id')->on('titles');
            $table->string('sn');
            $table->foreignId('checkout_user_id');
            $table->foreign('checkout_user_id')->references('id')->on('users');
            $table->dateTime('acquisition_date');
            $table->dateTime('checkout_date');
            $table->longText('damage_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copies');
    }
}
