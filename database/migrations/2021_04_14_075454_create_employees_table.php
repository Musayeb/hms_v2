<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->BigIncrements("emp_id");
            $table->Biginteger("emp_identify_id");
            $table->Biginteger('dep_id')->unsigned();
            $table->foreign('dep_id')->references('dep_id')->on('departments');
            $table->string("position");
            $table->string("tin_number");
            $table->integer("fees")->nullable();
            $table->string("f_name");
            $table->string("l_name");
            $table->string("father_name");
            $table->string("mother_name")->nullable();
            $table->string("passport_id");
            $table->string("gender",10);
            $table->string("m_status",20);
            $table->date("date_of_birth");
            $table->date("date_of_join");
            $table->date("end_of_contract");
            $table->string("phone_number",11);
            $table->string("email");
            $table->string("photo")->nullable();
            $table->string("current_address");
            $table->string("permanent_address");
            $table->integer("salary");
            $table->string("cv_file")->nullable();
            $table->string("contract_file")->nullable();
            $table->Biginteger('author')->unsigned();
            $table->foreign('author')->references('id')->on('users');
            $table->string("emergency_contact");
            $table->string("relationship");
            $table->string("type")->nullable();
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
        Schema::dropIfExists('employees');
    }
}
