<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_bills', function (Blueprint $table) {
            $table->bigIncrements('company_bill_id');
            $table->bigInteger('bill_number');
            $table->string('company_name');
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('total');           
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
        Schema::dropIfExists('company_bills');
    }
}
