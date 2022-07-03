<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseMidicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_midicines', function (Blueprint $table) {
            $table->BigIncrements('purchase_m_id');
            $table->Biginteger('midi_id')->unsigned();
            $table->string('supplier_name');           
            $table->foreign('midi_id')->references('midi_id')->on('midicines');
            $table->string('quantity');
            $table->string('purchase_price');
            $table->string('sale_price');
            $table->string('amount');
            $table->string('expiry_date');
            $table->string('batch_number')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('purchase_midicines');
    }
}
