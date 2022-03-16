<?php


namespace App\Policies;


use App\Models\Accounts;
use App\Models\Patients;

class DeathPolicy
{
    public function index(Accounts $loggedUser){
        return $loggedUser->isAdmin() || $loggedUser->isDoctor();
    }

    public function create(Accounts $account){
        return $account->isAdmin() ||$account->isDoctor() ;
    }

    public function store(Accounts $account,$patient_id){
        $patient = Patients::where('id','=',$patient_id)->first();
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }

    public function delete(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }

    public function update(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }
}
