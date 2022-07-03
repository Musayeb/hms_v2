<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaselabMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaselab_materials', function (Blueprint $table) {
            $table->BigIncrements('lab_purchase_id');
            $table->Biginteger('lab_m_id')->unsigned();
            $table->string('supplier_name');           
            $table->foreign('lab_m_id')->references('lab_m_id')->on('lab_materials');
            $table->string('quantity');
            $table->string('purchase_price');
            $table->string('sale_price');
            $table->string('amount');
            $table->string('expiry_date');
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
        Schema::dropIfExists('purchaselab_materials');
    }
}
