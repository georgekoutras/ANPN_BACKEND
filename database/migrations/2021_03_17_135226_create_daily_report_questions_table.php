<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDailyReportQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_report_questions', function (Blueprint $table) {
            $table->id();
            $table->string("text");
            $table->string("category");
            $table->string("type");
            $table->string("label")->nullable();
            $table->boolean("active")->default(true);
            $table->bigInteger('parent_id')->unsigned()->nullable()->index('parent_question_id_index');
            $table->foreign('parent_id', 'parent_question_id_foreign')
                ->references('id')->on('daily_report_questions');
        });

        DB::table('daily_report_questions')->insert([
            ['text'=> 'Αυξήθηκε η δύσπνοια;','category' => 'daily','type'=>'check','label'=>'q1','active'=>true,'parent_id'=>null],
            ['text'=> 'Αυξήθηκε ο βήχας σας;','category' => 'daily','type'=>'check','label'=>'q2','active'=>true,'parent_id'=>null],
            ['text'=> 'Άλλαξε το σάλιο σας;','category' => 'daily','type'=>'check','label'=>'q3','active'=>true,'parent_id'=>null],
            ['text'=> 'Είχατε πόνο στο στήθος ή δυσφορία;','category' => 'daily','type'=>'check','label'=>'q4','active'=>true,'parent_id'=>null],
            ['text'=> 'Πήρατε τα ίδια φάρμακα; Ή τα αυξήσατε;','category' => 'daily','type'=>'check','label'=>'q5','active'=>true,'parent_id'=>null],
            ['text'=> 'Μπορείτε να κάνετε την καθημερινή δουλειά που κάνατε πριν;','category' => 'daily','type'=>'check','label'=>'q1a','active'=>true,'parent_id'=>1],
            ['text'=> 'Μπορείτε να υποστηρίξετε τον εαυτό σας; (τουαλέτα, ντους)','category' => 'daily','type'=>'check','label'=>'q1b','active'=>true,'parent_id'=>1],
            ['text'=> 'Μπορείτε να περπατήσετε;','category' => 'daily','type'=>'check','label'=>'q1c','active'=>true,'parent_id'=>1],
            ['text'=> 'Είναι το σάλιο σας κίτρινο;','category' => 'daily','type'=>'check','label'=>'q3a','active'=>true,'parent_id'=>3],
            ['text'=> 'Είναι το σάλιο σας πράσινο;','category' => 'daily','type'=>'check','label'=>'q3b','active'=>true,'parent_id'=>3],
            ['text'=> 'Περιέχει αίμα  το σάλιο σας;','category' => 'daily','type'=>'check','label'=>'q3c','active'=>true,'parent_id'=>3],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_report_questions');
    }
}
