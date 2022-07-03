<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharma_bills', function (Blueprint $table) {
            $table->BigIncrements('bill_id');
            $table->bigInteger('bill_no');
            // $table->Biginteger('patient_id')->unsigned()->nullable();
            // $table->foreign('patient_id')->references('patient_id')->on('patients');
            $table->string('patient_name')->nullable();
            // $table->string('p_identify')->nullable();
            // $table->Biginteger('emp_id')->unsigned();
            // $table->foreign('emp_id')->references('emp_id')->on('employees');
            // $table->string('p_type')->nullable();
            // $table->Biginteger('dep_id')->unsigned();
            // $table->foreign('dep_id')->references('dep_id')->on('departments');
            $table->string('total');
            $table->string('discount')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('pharma_bills');
    }
}
