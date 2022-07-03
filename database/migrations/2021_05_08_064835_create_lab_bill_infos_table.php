<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabBillInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_bill_infos', function (Blueprint $table) {
            $table->bigIncrements('lab_bill_ifo_id');            
            $table->Biginteger('bill_id')->unsigned();
            $table->foreign('bill_id')->references('bill_id')->on('lab_bills');
            $table->Biginteger('test_id')->unsigned();
            $table->foreign('test_id')->references('test_id')->on('tests');
            $table->Biginteger('author')->unsigned();
            $table->foreign('author')->references('id')->on('users');
            $table->string('total');
            $table->boolean('eod_count')->nullable()->default(false);

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
        Schema::dropIfExists('lab_bill_infos');
    }
}
