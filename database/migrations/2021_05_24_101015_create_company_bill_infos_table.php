<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBillInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_bill_infos', function (Blueprint $table) {
            $table->bigIncrements('company_bill_id');
            
            $table->Biginteger('company_bill_id1')->unsigned();
            $table->foreign('company_bill_id')->references('company_bill_id')->on('company_bills');

            $table->bigInteger('bill_number');
            $table->string('receiver_name');
            $table->string('paid_amount');
            $table->string('receipt_number')->nullable();
            $table->Biginteger('author')->unsigned();
            $table->boolean('eod_count')->nullable()->default(false);
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
        Schema::dropIfExists('company_bill_infos');
    }
}
