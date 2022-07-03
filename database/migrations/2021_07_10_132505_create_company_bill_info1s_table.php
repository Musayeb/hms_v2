<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBillInfo1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_bill_info1s', function (Blueprint $table) {
            $table->id();
            $table->Biginteger('author')->unsigned();
            $table->foreign('author')->references('id')->on('users');
            $table->string('amount');
            $table->date('date');
            $table->string('receipt_number')->nullable();
            $table->Biginteger('company_bill_id')->unsigned();
            $table->foreign('company_bill_id')->references('company_bill_id')->on('company_bills');
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
        Schema::dropIfExists('company_bill_info1s');
    }
}
