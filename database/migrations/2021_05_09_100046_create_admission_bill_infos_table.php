<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionBillInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_bill_infos', function (Blueprint $table) {
            $table->bigIncrements('bill_info');
            $table->Biginteger('admission_id')->unsigned();
            $table->foreign('admission_id')->references('admission_id')->on('admission_bills');
            $table->string('charges_type');
            $table->text('charge_description');
            $table->string('amount');
            $table->string('status');
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
        Schema::dropIfExists('admission_bill_infos');
    }
}
