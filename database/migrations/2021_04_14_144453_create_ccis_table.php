<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCcisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index('ccis_patient_id_index');
            $table->foreign('patient_id', 'ccis_patient_id_foreign')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->date('diagnose_date')->nullable();
            $table->tinyInteger( 'myocardialInfarction')->default(0);
            $table->tinyInteger( 'congestiveHeartFailure')->default(0);
            $table->tinyInteger( 'peripheralVascularDisease')->default(0);
            $table->tinyInteger( 'cerebrovascularDisease')->default(0);
            $table->tinyInteger( 'dementia')->default(0);
            $table->tinyInteger( 'chronicPulmonaryDisease')->default(0);
            $table->tinyInteger( 'connectiveTissueDisease')->default(0);
            $table->tinyInteger( 'ulcerDisease')->default(0);
            $table->tinyInteger( 'liverDiseaseMild')->default(0);
            $table->tinyInteger( 'diabetes')->default(0);
            $table->tinyInteger( 'hemiplegia')->default(0);
            $table->tinyInteger( 'renalDiseaseModerateOrSevere')->default(0);
            $table->tinyInteger( 'diabetesWithEndOrganDamage')->default(0);
            $table->tinyInteger( 'anyTumor')->default(0);
            $table->tinyInteger( 'leukemia')->default(0);
            $table->tinyInteger( 'malignantLymphoma')->default(0);
            $table->tinyInteger( 'liverDiseaseModerateOrSevere')->default(0);
            $table->tinyInteger( 'metastaticSolidMalignancy')->default(0);
            $table->tinyInteger( 'aids')->default(0);
            $table->tinyInteger( 'noConditionAvailable')->default(0);
            $table->integer('totalCharlson')->default(0);

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
        Schema::dropIfExists('ccis');
    }
}
