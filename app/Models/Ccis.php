<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ccis extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FormatDates;

    protected $fillable  = ['patient_id','diagnose_date','myocardialInfarction','congestiveHeartFailure','peripheralVascularDisease','cerebrovascularDisease',
        'dementia','chronicPulmonaryDisease','connectiveTissueDisease','ulcerDisease','liverDiseaseMild','diabetes','hemiplegia','renalDiseaseModerateOrSevere',
        'diabetesWithEndOrganDamage','anyTumor','leukemia','malignantLymphoma','liverDiseaseModerateOrSevere','metastaticSolidMalignancy','aids','noConditionAvailable'
        ,'totalCharlson'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function DEFAULT_SORTING(){
        return [
            'sort' => 'created_at',
            'order' => 'desc'
        ];
    }

    public function patient(){
        return $this->belongsTo(Patients::class);
    }
}
