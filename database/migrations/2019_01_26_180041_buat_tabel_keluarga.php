<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class BuatTabelKeluarga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('families', function(Blueprint $table){
            $table->increments('id');
            $table->string('nik');
            $table->string('name',50);
            $table->string('address')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->smallInteger('education_id');
            $table->string('occupation_desc',255)->nullable();
            $table->smallInteger('occupation_id');
            $table->smallInteger('religion_id');
            $table->string('marital_status')->nullable();
            $table->smallInteger('isDied');
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
        Schema::dropIfExists('families');
    }
}