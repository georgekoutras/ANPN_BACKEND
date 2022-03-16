<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reading extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FormatDates;

    protected $fillable  = ['patient_id','diagnose_date','status','weight','height','pxy','mmrc',
        'smoker','notes','fev1','fev1_pro','fvc','fvc_pro','fev1_fvc','rv',
        'rv_pro','tlc','tlc_pro','rv_tlc','satO2_pro','dlco_pro','pao2','paco2','hco3',
        'pH','fvc_pre','fvc_pre_pro','fev1_pre','fev1_pre_pro','fev1_fvc_pre','fef25_75_pre_pro','pef_pre_pro','tlc_pre',
        'tlc_pre_pro','frc_pre','frc_pre_pro','rv_pre','rv_pre_pro','kco_pro','hematocrit','fvc_post','del_fvc_pro',
        'fev1_post','del_fev1_post','del_fef25_75_pro','del_pef_pro'];

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
