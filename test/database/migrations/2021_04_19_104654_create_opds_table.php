<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opds', function (Blueprint $table) {
            $table->BigIncrements('opd_id');
            $table->bigInteger('patient_id');
            $table->string('o_f_name');
            $table->string('o_l_name');
            $table->string('phone');
            $table->string('gender');
            $table->string('age');
            $table->date('date')->nullable();
            $table->string('referred')->nullable();
            $table->Biginteger('author')->unsigned();
            $table->foreign('author')->references('id')->on('users');
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
        Schema::dropIfExists('opds');
    }
}
