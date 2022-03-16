<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AccessToken extends ModelWithTimestamps
{

    protected $fillable = [
        'account_id',
        'access_token',
        'refresh_token'
    ];

    public function account(){
        return $this->belongsTo(Accounts::class);
    }

    public static function generateAccessToken(Accounts $account){
        $time = time() - 7200;

        $string = $account->id.'|'.time().'|'.(time()+1800).'|'.$time;
        $accessToken = base64_encode(Crypt::encryptString($string));
        $refreshToken = base64_encode(Str::random(40));

        return new AccessToken(
            ['account_id' => $account->id, 'access_token' => $accessToken, 'refresh_token' => $refreshToken]
        );
    }
}
