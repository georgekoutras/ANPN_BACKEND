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
            $table->id();
            $table->bigInteger('account_id')->unsigned()->index('patient_account_id_index');
            $table->foreign('account_id', 'patient_account_id_foreign')
                ->references('id')->on('accounts')->onDelete('cascade');
            $table->bigInteger('doctor_id')->unsigned()->index('patient_doctor_id_index');
            $table->foreign('doctor_id', 'patient_doctor_id_foreign')
                ->references('id')->on('accounts')->onDelete('cascade');
            $table->string('social_id')->unique();
            $table->tinyInteger( 'sex');
            $table->date('birth_date');
            $table->date('first_diagnose_date');
            $table->string('address');
            $table->string('land_line')->nullable();
            $table->string('file_id');

            $table->timestamps();
            $table->softDeletes();

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
