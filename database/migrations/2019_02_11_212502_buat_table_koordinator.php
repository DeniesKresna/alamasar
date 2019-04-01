<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTableKoordinator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('coordinators', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('coordinator_numb');
            $table->smallInteger('card_id');
            $table->string('id_numb');
            $table->enum('sex',['L','P']);
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->string('city')->nullable();
            $table->string('note')->nullable();
            $table->string('job_desc',300)->nullable();
            $table->string('account_numb')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_city')->nullable();
            $table->smallInteger('isActive');
            $table->integer('creator_id')->unsigned();
            $table->integer('updater_id')->unsigned();
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
        Schema::dropIfExists('coordinators');
    }
}
