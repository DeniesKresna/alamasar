<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class BuatTabelSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('students', function(Blueprint $table){
            $table->increments('id');
            $table->string('id_numb');
            $table->smallInteger('card_id');
            $table->string('student_numb');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->enum('sex',['L','P']);
            $table->string('address')->nullable();
            $table->string('domicile')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->smallInteger('religion_id');
            $table->string('note')->nullable();
            $table->smallInteger('education_id');
            $table->string('education_desc')->nullable();
            $table->string('status_desc')->nullable();
            $table->string('status',50);
            $table->string('account_numb')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('cc_numb')->nullable();
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
        Schema::dropIfExists('students');
    }
}