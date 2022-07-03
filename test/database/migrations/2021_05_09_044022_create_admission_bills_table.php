<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_bills', function (Blueprint $table) {
            $table->bigIncrements('admission_id');
            $table->string('bill_number');
            $table->date('admission_date');
            $table->Biginteger('patient_id')->unsigned();
            
            $table->Biginteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees');
            $table->Biginteger('dep_id')->unsigned();
            $table->foreign('dep_id')->references('dep_id')->on('departments');
            $table->string('operate_type');
            $table->string('operate_id');    
            $table->foreign('patient_id')->references('patient_id')->on('patients');
            $table->string('deposit_amount');    
            $table->string('discount')->nullable();    
            $table->Biginteger('author')->unsigned();
            $table->foreign('author')->references('id')->on('users');   
            
            $table->timestamp('discharge_date')->nullable();
            $table->string('discharged_by')->nullable();

            
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
        Schema::dropIfExists('admission_bills');
    }
}
