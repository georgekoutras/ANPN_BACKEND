<?php

namespace App\Models;

use App\Models\Mobile\AccountDeviceToken;
use App\Traits\FormatDates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Accounts extends Authenticatable
{
    use SoftDeletes;
    use FormatDates;

    protected $fillable  = ['role','second_name','first_name','last_name','email','mobile','notification_enabled','notification_mode'];

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

    public function isAdmin(){
        return $this->role  == 'administrator';
    }

    public function isDoctor(){
        return $this->role  == 'doctor';
    }

    public function isPatient(){
        return $this->role  == 'patient';
    }

    public function role(){
        return $this->role;
    }

    public function getFullName(){
        return $this->first_name.' '.$this->last_name;
    }

    public function patientInfo(){
        return $this->hasOne(Patients::class,'account_id');
    }

    public function patients(){
        return $this->hasMany(Patients::class,'doctor_id');
    }

    public function hasPatient($patient){
        return $this->patients->contains($patient);
    }

    public function getDeviceTokens(){
        return $this->belongsToMany(AccountDeviceToken::class);
    }

/*    public function getBirthDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone(request()->user()->timezone)
                        ->format('d/m/Y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('UTC')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }*/
}
