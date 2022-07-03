<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_operations', function (Blueprint $table) {
            $table->BigIncrements('patient_s_del_pro_id');
            $table->string('type');
            
            $table->Biginteger('procedure_id')->unsigned()->nullable();
            $table->foreign('procedure_id')->references('procedure_id')->on('procedures');
            $table->Biginteger('surgery_id')->unsigned()->nullable();
            $table->foreign('surgery_id')->references('surgery_id')->on('surgeries');

            $table->date('date');
            $table->string('time');
            $table->Biginteger('patient_id')->unsigned();
            $table->foreign('patient_id')->references('patient_id')->on('patients');
            $table->Biginteger('dep_id')->unsigned();
            $table->foreign('dep_id')->references('dep_id')->on('departments');
            $table->Biginteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees');
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
        Schema::dropIfExists('patient_operations');
    }
}
