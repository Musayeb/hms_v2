<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndOfTheDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('end_of_the_days', function (Blueprint $table) {
            $table->BigIncrements('id');
            
            $table->BigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->bigInteger('pharmacy');
            $table->bigInteger('laboratory');
            $table->bigInteger('admission');
            $table->bigInteger('opd');
            $table->bigInteger('overtimepayment');
            $table->bigInteger('nursebill');
            $table->bigInteger('partialpayment');
            $table->bigInteger('medicalcompany');
            $table->bigInteger('extra_income');
            $table->bigInteger('dailyexpenses');
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
        Schema::dropIfExists('end_of_the_days');
    }
}
