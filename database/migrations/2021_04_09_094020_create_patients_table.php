<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->BigIncrements('patient_id');
            
            $table->Biginteger('dep_id')->unsigned();
            $table->foreign('dep_id')->references('dep_id')->on('departments');

            $table->string('f_name');
            $table->string('l_name');
            $table->date('dob');
            $table->string('gender');
            $table->string('phone_number');
            $table->string('blood_g');
            $table->string('address');
            $table->string('emergency_contact');
            $table->string('relationship');
            $table->bigInteger('patient_idetify_number');
            $table->Biginteger('author')->unsigned();
            $table->foreign('author')->references('id')->on('users');
            $table->integer('age');
            $table->text('remark')->nullable();
            $table->string('occupation');
            $table->string('marital_status');
            $table->string('referred')->nullable();
            $table->text('allergies')->nullable();           
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
        Schema::dropIfExists('patients');
    }
}
