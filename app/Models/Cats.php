<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cats extends Model
{
    use SoftDeletes;
    use HasFactory;
    use FormatDates;

    protected $fillable  = ['patient_id','diagnose_date','total_cat_scale','q1','q2','q3','q4','q5','q6','q7','q8','status'];

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
