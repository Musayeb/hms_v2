<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMidicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midicines', function (Blueprint $table) {
            $table->BigIncrements('midi_id');
            $table->string('barcode')->nullable();
            $table->Biginteger('ph_main_cat_id')->unsigned();
            $table->foreign('ph_main_cat_id')->references('ph_main_cat_id')->on('pharma__main__catagories');
            $table->string('medicine_name');
            $table->string('quantitiy')->nullable();
            $table->string('sold_quantity')->nullable();
            $table->string('company');
            $table->string('expiry_date')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('midicines');
    }
}
