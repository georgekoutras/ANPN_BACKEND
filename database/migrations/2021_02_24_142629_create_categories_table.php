<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->index('category_patient_id_index');
            $table->foreign('patient_id', 'category_patient_id_foreign')
                ->references('id')->on('patients')->onDelete('cascade');
            $table->date('diagnose_date')->nullable();
            $table->integer( 'total_cat_scale')->nullable()->default(0);
            $table->integer( 'q1')->default(-1);
            $table->integer( 'q2')->default(-1);
            $table->integer( 'q3')->default(-1);
            $table->integer( 'q4')->default(-1);
            $table->integer( 'q5')->default(-1);
            $table->integer( 'q6')->default(-1);
            $table->integer( 'q7')->default(-1);
            $table->integer( 'q8')->default(-1);
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
        Schema::dropIfExists('categories');
    }
}
