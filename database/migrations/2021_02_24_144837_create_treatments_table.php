<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index('treatment_patient_id_index');
            $table->foreign('patient_id', 'treatment_patient_id_foreign')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->date('diagnose_date')->nullable();
            $table->enum('status', ['baseline', 'exacerbation']);
            $table->tinyInteger('short_acting_b2')->nullable();
            $table->tinyInteger('long_acting_b2')->nullable();
            $table->tinyInteger('ultra_long_acting_b2')->nullable();
            $table->tinyInteger('steroids_inhaled')->nullable();
            $table->tinyInteger('steroids_oral')->nullable();
            $table->tinyInteger('sama')->nullable();
            $table->tinyInteger('lama')->nullable();
            $table->tinyInteger('pdef4_inhalator')->nullable();
            $table->tinyInteger('theophyline')->nullable();
            $table->tinyInteger('mycolytocis')->nullable();
            $table->tinyInteger('antibiotics')->nullable();
            $table->tinyInteger('antiflu')->nullable();
            $table->tinyInteger('antipneum')->nullable();
            $table->tinyInteger('ltot')->nullable();
            $table->date('ltot_start_date')->nullable()->default(null);
            $table->enum('ltot_device', ['none', 'Cylinder', 'Liquid', 'Concetrator'])->nullable();
            $table->tinyInteger('niv')->nullable();
            $table->date('ventilation_start_date')->nullable()->default(null);
            $table->enum('ventilation_device', ['none', 'BiPAP', 'CPAP'])->nullable();
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
        Schema::dropIfExists('treatments');
    }
}
