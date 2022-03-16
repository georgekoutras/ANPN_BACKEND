<?php

namespace App\Models;

use App\Traits\FormatDates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patients extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FormatDates;

    protected $fillable  = ['doctor_id','social_id','birth_date','first_diagnose_date','sex','land_line','address','file_id'];


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function DEFAULT_SORTING(){
        return [
            'sort' => 'id',
            'order' => 'asc'
        ];
    }

    public function getId(){
        return $this->id;
    }

    public function doctor(){
        return $this->belongsTo(Accounts::class,'doctor_id');
    }

    public function account(){
        return $this->belongsTo(Accounts::class,'account_id');
    }

    public function death(){
        return $this->belongsTo(Deaths::class,'patient_id');
    }

}
