<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelWithTimestamps extends Model
{

    protected $dateFormat = 'Y-m-d H:i:s';

    public function fromDateTime($value)
    {
        if (($x = strpos($value, '.')) !== false) {
            $length = strlen(substr($value, $x, strlen($value)-1));
            $value = substr($value, 0 ,$length*-1);
            return parent::fromDateTime($value);
        }else {
            return parent::fromDateTime($value);
        }
    }

    public function asDateTime($value)
    {

        if (($x = strpos($value, '.')) !== false) {
            $length = strlen(substr($value, $x, strlen($value)-1));
            $value = substr($value, 0 ,$length*-1);
            return parent::asDateTime($value);
        }else {

            return parent::asDateTime($value);
        }
    }}
