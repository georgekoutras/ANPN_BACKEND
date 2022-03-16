<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index('daily_report_patient_id_index');
            $table->foreign('patient_id', 'daily_report_patient_id_foreign')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->date('date')->nullable()->default(null);
            $table->tinyInteger( 'q1');
            $table->tinyInteger( 'q2');
            $table->tinyInteger( 'q3');
            $table->tinyInteger( 'q4');
            $table->tinyInteger( 'q5');
            $table->tinyInteger( 'q1a')->nullable()->default(0);
            $table->tinyInteger( 'q1b')->nullable()->default(0);
            $table->tinyInteger( 'q1c')->nullable()->default(0);
            $table->tinyInteger( 'q3a')->nullable()->default(0);
            $table->tinyInteger( 'q3b')->nullable()->default(0);
            $table->tinyInteger( 'q3c')->nullable()->default(0);
            $table->float('satO2')->nullable()->default(null);
            $table->float('walkingDist')->nullable()->default(null);
            $table->float('temperature')->nullable()->default(null);
            $table->float('pefr')->nullable()->default(null);
            $table->float('heartRate')->nullable()->default(null);

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
        Schema::dropIfExists('daily_reports');
    }
}
