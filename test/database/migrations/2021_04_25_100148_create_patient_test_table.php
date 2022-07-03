<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_test', function (Blueprint $table) {
            $table->BigIncrements('patient_test_id');
            $table->Biginteger('test_id')->unsigned();
            $table->foreign('test_id')->references('test_id')->on('tests');
            $table->Biginteger('dep_id')->unsigned();
            $table->foreign('dep_id')->references('dep_id')->on('departments');
            $table->Biginteger('opd_id')->unsigned();
            $table->foreign('opd_id')->references('opd_id')->on('opds');
            $table->Biginteger('fees');
            $table->string('description')->nullable();
            $table->string('status');
            $table->boolean('eod_count')->nullable()->default(false);
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
        Schema::dropIfExists('patient_test');
    }
}
