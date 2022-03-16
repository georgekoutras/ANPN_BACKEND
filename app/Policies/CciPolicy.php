<?php


namespace App\Policies;


use App\Models\Accounts;
use App\Models\Patients;

class CciPolicy
{
    public function index(Accounts $loggedUser){
        return $loggedUser->isAdmin() || $loggedUser->isDoctor();
    }

    public function patientCcis(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }

    public function store(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }

    public function delete(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }

    public function update(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient));
    }
}
