<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contestWeeks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contestId');
            $table->integer('week');
            $table->dateTime('startDt');
            $table->dateTime('endDt');
            $table->dateTime('weighDt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contestWeeks');
    }
}
