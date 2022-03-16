<?php


namespace App\Traits;

use Carbon\Carbon;

trait FormatDates
{
    public $is_for_field = false;

    public function getCreatedAtAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/Y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function getDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function getBirthDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function getFirstDiagnoseDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function getLtotStartDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function getVentilationStartDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('Y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function getDiagnoseDateAttribute($value){
        if ($this->is_for_field){
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('d/m/y');
                }
            }
        }else {
            if ($value !== null) {
                if (!is_null(request()->user())) {
                    return Carbon::createFromTimestamp(strtotime($value))
                        ->timezone('Europe/Athens')
                        ->format('y-m-d H:i:s');
                }
            }
        }
        return $value;
    }

    public function convertDate($value, $timezone){

        $date =  Carbon::createFromFormat('d/m/y', $value, $timezone);
        $date->setTimezone('UTC');
        $date = $date->format('Y-m-d H:i:s').'.000';
        return $date;
    }
}
