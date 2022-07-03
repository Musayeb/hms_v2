<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaBillInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharma_bill_infos', function (Blueprint $table) {
            $table->bigIncrements('pharma_bill_ifo_id');            
            $table->Biginteger('bill_id')->unsigned();
            $table->foreign('bill_id')->references('bill_id')->on('pharma_bills');
            $table->Biginteger('midi_id')->unsigned();
            $table->foreign('midi_id')->references('midi_id')->on('midicines');
            $table->string('expiry_date');
            $table->string('quanitity');
            $table->string('price');
            $table->string('total');
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
        Schema::dropIfExists('pharma_bill_infos');
    }
}
