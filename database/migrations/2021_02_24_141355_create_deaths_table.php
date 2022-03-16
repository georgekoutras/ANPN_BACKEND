<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deaths', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index('death_patient_id_index');
            $table->foreign('patient_id', 'death_patient_id_foreign')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->dateTime('date')->nullable();
            $table->tinyInteger( 'cardiovascular')->default(0);
            $table->tinyInteger( 'respiratory')->default(0);
            $table->tinyInteger( 'infectious_disease')->default(0);
            $table->tinyInteger( 'malignancy')->default(0);
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('deaths');
    }
}
