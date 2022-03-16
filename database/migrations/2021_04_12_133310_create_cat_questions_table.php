<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCatQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_questions', function (Blueprint $table) {
            $table->id();
            $table->string("text");
            $table->string("category");
            $table->string("type");
            $table->string("label")->nullable();
            $table->string("default_min_text");
            $table->string("default_max_text");
            $table->boolean("active")->default(true);
        });

        DB::table('cat_questions')->insert([
            ['text'=> 'Βήχω;','category' => 'cats','type'=>'radio','label'=>'q1','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','active'=>true],
            ['text'=> 'Έχω φλέματα;','category' => 'cats','type'=>'radio','label'=>'q2','default_min_text'=>'Καθόλου','default_max_text'=> 'Πολλά','active'=>true],
            ['text'=> 'Έχω σφίξιμο στο στήθος;','category' => 'cats','type'=>'radio','label'=>'q3','default_min_text'=>'Όχι','default_max_text'=> 'Έντονο','active'=>true],
            ['text'=> 'Οι δραστηριότητες μου στο σπίτι είναι περιορισμένες;','category' => 'cats','type'=>'radio','label'=>'q4','default_min_text'=>'Καθόλου','default_max_text'=> 'Πολύ','active'=>true],
            ['text'=> 'Λαχανιάζω σε ανηφόρα ή σε ανάβαση σκάλας ενός ορόφου;','category' => 'cats','type'=>'radio','label'=>'q5','default_min_text'=>'Όχι','default_max_text'=> 'Πολύ','active'=>true],
            ['text'=> 'Έχω ήρεμο υπνο;','category' => 'cats','type'=>'radio','label'=>'q6','default_min_text'=>'Ναι','default_max_text'=> 'Όχι','active'=>true],
            ['text'=> 'Έχω αυτοπεποίθηση κατα την έξοδο από το σπίτι; (τουαλέτα, ντους)','category' => 'cats','type'=>'radio','label'=>'q7','default_min_text'=>'Ναι','default_max_text'=> 'Καθόλου','active'=>true],
            ['text'=> 'Έχω ενέργεια;','category' => 'cats','type'=>'radio','label'=>'q8','default_min_text'=>'Πολλή','default_max_text'=> 'Καθόλου','active'=>true],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_questions');
    }
}
