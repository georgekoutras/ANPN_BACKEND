<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCcqQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccq_questions', function (Blueprint $table) {
            $table->id();
            $table->string("text");
            $table->string("category");
            $table->string("type");
            $table->string("label")->nullable();
            $table->string("default_min_text");
            $table->string("default_max_text");
            $table->boolean("active")->default(true);
            $table->integer('group');
        });

        DB::table('ccq_questions')->insert([
            ['text'=> 'Λαχάνιασμα όταν είσατε σε ανάπαυση;','category' => 'ccqs','type'=>'radio','label'=>'q1','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','group'=>1,'active'=>true],
            ['text'=> 'Λαχάνιασμα όταν είχατε σωματικές δραστηριότητες;','category' => 'ccqs','type'=>'radio','label'=>'q2','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','group'=>1,'active'=>true],
            ['text'=> 'Ανύσηχος/η μήπως κολλήσετε κάποιο κρυολόγημα ή μήπως χειροτερέψει η αναπνοή σας;','category' => 'ccqs','type'=>'radio','label'=>'q3','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','group'=>1,'active'=>true],
            ['text'=> 'θλιμένος/η (λυπημένος/η) λόγω των αναπνευστικών σας προβλημάτων;','category' => 'ccqs','type'=>'radio','label'=>'q4','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','group'=>1,'active'=>true],
            ['text'=> 'Βήχατε;','category' => 'ccqs','type'=>'radio','label'=>'q5','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','group'=>2,'active'=>true],
            ['text'=> 'Είχατε φλέμματα;','category' => 'ccqs','type'=>'radio','label'=>'q6','default_min_text'=>'Ποτέ','default_max_text'=> 'Συνέχεια','group'=>2,'active'=>true],
            ['text'=> 'Εντατικές σωματικές δραστηριότητες (ανάβαση σκαλών, αθλητικές δραστηριότητες κλπ);','category' => 'ccqs','type'=>'radio','label'=>'q7','default_min_text'=>'Καθόλου','default_max_text'=> 'Πάρα πολύ','group'=>3,'active'=>true],
            ['text'=> 'Μέτριες σωματικές δραστηριότητες (περπάτημα, νοικοκυριό, μεταφορά πραγμάτων κλπ);','category' => 'ccqs','type'=>'radio','label'=>'q8','default_min_text'=>'Καθόλου','default_max_text'=> 'Πάρα πολύ','group'=>3,'active'=>true],
            ['text'=> 'Καθημερινές δραστηριότητες στο σπίτι (ντύνεστε, πλένεστε κλπ);','category' => 'ccqs','type'=>'radio','label'=>'q9','default_min_text'=>'Καθόλου','default_max_text'=> 'Πάρα πολύ','group'=>3,'active'=>true],
            ['text'=> 'Κοινωνικές δραστηριότητες (μιλάτε, να είστε με παιδιά, επισκέψεις κλπ);','category' => 'ccqs','type'=>'radio','label'=>'q10','default_min_text'=>'Καθόλου','default_max_text'=> 'Πάρα πολύ','group'=>3,'active'=>true],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ccq_questions');
    }
}
