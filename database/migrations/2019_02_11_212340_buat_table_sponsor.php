<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTableSponsor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sponsors', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('type', 50);
            $table->string('sponsor_numb');
            $table->smallInteger('card_id');
            $table->string('id_numb');
            $table->string('address')->nullable();
            $table->smallInteger('nation_id')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('job_desc',300)->nullable();
            $table->string('account_numb')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('photo')->nullable();
            $table->date('start_date')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('sponsors');
    }
}
