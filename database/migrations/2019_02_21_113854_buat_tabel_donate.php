<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTabelDonate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('donates', function(Blueprint $table){
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('coordinator_id')->unsigned();
            $table->integer('sponsor_id')->nullable();
            $table->integer('amount')->nullable();
            $table->date('send_time')->nullable();
            $table->smallInteger('isVerified')->nullable();
            $table->string('photo')->nullable();
            $table->integer('creator_id')->nullable();
            $table->integer('updater_id')->nullable();
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
        //
        Schema::dropIfExists('donates');
    }
}
