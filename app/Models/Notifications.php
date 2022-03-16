<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model
{
    use SoftDeletes;
    use FormatDates;

    protected $fillable  = ['account_id','notification_date','notification_type','notification_message'];

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

    public function account(){
        return $this->belongsTo(Accounts::class,'account_id');
    }
}
