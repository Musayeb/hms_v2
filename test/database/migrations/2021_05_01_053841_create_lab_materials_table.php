<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_materials', function (Blueprint $table) {
            $table->BigIncrements('lab_m_id');
            $table->Biginteger('lab_cat_id')->unsigned();
            $table->foreign('lab_cat_id')->references('lab_cat_id')->on('lab_catagories');
            $table->string('material_name');
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
        Schema::dropIfExists('lab_materials');
    }
}
