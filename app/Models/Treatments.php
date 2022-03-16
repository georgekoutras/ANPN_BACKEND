<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatments extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FormatDates;

    protected $fillable  = ['patient_id','diagnose_date','status','ltot_device','ltot_start_date','ventilation_device','ventilation_start_date',
        'antibiotics','antiflu','antipneum','lama','long_acting_b2','ltot','mycolytocis','niv',
        'pdef4_inhalator','sama','short_acting_b2','steroids_inhaled','steroids_oral','theophyline','ultra_long_acting_b2','notes'];

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
