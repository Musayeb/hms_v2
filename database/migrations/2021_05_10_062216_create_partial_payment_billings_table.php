<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartialPaymentBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partial_payment_billings', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->integer('bill_number');
            $table->string('doctor_name');
            $table->string('doctor_phone_number');
            $table->foreign('patient_id')->references('patient_id')->on('patients');
            $table->string('patient_name')->nullable();
            $table->string('department');
            $table->date('date')->nullable();
            $table->integer('docter_charges');
            $table->string('description');
            $table->boolean('eod_count')->nullable()->default(false);
            $table->Biginteger('author')->unsigned()->nullable();
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
        Schema::dropIfExists('partial_payment_billings');
    }
}
