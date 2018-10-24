<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contestUsers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId');
            $table->integer('contestId');
            $table->double('startLbs', 3, 2)->nullable();
            $table->integer('status');
            $table->integer('inviteId')->nullable();
            $table->boolean('owner');
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
        Schema::dropIfExists('contestUsers');
    }
}
