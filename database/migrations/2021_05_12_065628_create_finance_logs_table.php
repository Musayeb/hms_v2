<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_logs', function (Blueprint $table) {
            $table->bigIncrements('f_id');
            $table->string('payment_type')->nullable();
            $table->string('type')->nullable();
            $table->string('bill_id')->nullable();
            $table->string('total')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->Biginteger('author')->unsigned()->nullable();
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
        Schema::dropIfExists('finance_logs');
    }
}
