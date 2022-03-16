<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index('readings_patient_id_index');
            $table->foreign('patient_id', 'readings_patient_id_foreign')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->date('diagnose_date')->nullable();
            $table->integer('weight')->nullable()->default(null);
            $table->integer('height')->nullable()->default(null);
            $table->integer('pxy')->nullable()->default(null);
            $table->integer('mmrc')->nullable()->default(null);
            $table->integer('smoker')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->float('fev1')->nullable()->default(null);
            $table->float('fev1_pro')->nullable()->default(null);
            $table->float('fvc')->nullable()->default(null);
            $table->float('fvc_pro')->nullable()->default(null);
            $table->float('fev1_fvc')->nullable()->default(null);
            $table->float('rv')->nullable()->default(null);
            $table->float('rv_pro')->nullable()->default(null);
            $table->float('tlc')->nullable()->default(null);
            $table->float('tlc_pro')->nullable()->default(null);
            $table->float('rv_tlc')->nullable()->default(null);
            $table->float('satO2_pro')->nullable()->default(null);
            $table->float('dlco_pro')->nullable()->default(null);
            $table->float('pao2')->nullable()->default(null);
            $table->float('paco2')->nullable()->default(null);
            $table->float('hco3')->nullable()->default(null);
            $table->float('pH')->nullable()->default(null);
            $table->float('fvc_pre')->nullable()->default(null);
            $table->float('fvc_pre_pro')->nullable()->default(null);
            $table->float('fev1_pre')->nullable()->default(null);
            $table->float('fev1_pre_pro')->nullable()->default(null);
            $table->float('fev1_fvc_pre')->nullable()->default(null);
            $table->float('fef25_75_pre_pro')->nullable()->default(null);
            $table->float('pef_pre_pro')->nullable()->default(null);
            $table->float('tlc_pre')->nullable()->default(null);
            $table->float('tlc_pre_pro')->nullable()->default(null);
            $table->float('frc_pre')->nullable()->default(null);
            $table->float('frc_pre_pro')->nullable()->default(null);
            $table->float('rv_pre')->nullable()->default(null);
            $table->float('rv_pre_pro')->nullable()->default(null);
            $table->float('kco_pro')->nullable()->default(null);
            $table->float('hematocrit')->nullable()->default(null);
            $table->float('fvc_post')->nullable()->default(null);
            $table->float('del_fvc_pro')->nullable()->default(null);
            $table->float('fev1_post')->nullable()->default(null);
            $table->float('del_fev1_post')->nullable()->default(null);
            $table->float('del_fef25_75_pro')->nullable()->default(null);
            $table->float('del_pef_pro')->nullable()->default(null);

            $table->enum('status', ['baseline', 'exacerbation']);

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
        Schema::dropIfExists('readings');
    }
}
