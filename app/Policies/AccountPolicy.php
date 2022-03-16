<?php

namespace App\Policies;

use App\Models\Accounts;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function index(Accounts $loggedUser){
        return $loggedUser->isAdmin() || $loggedUser->isDoctor();
    }

    public function create(Accounts $loggedUser){
        return $loggedUser->isAdmin() || $loggedUser->isDoctor();
    }

    public function delete(Accounts $loggedUser , Accounts $account){
        return $loggedUser->isAdmin() || ($loggedUser->isDoctor() && $loggedUser->hasPatient($account->patientInfo));
    }

    public function update(Accounts $loggedUser, Accounts $account){
        return $loggedUser->isAdmin() || ($loggedUser->isDoctor() && $loggedUser->hasPatient($account->patientInfo)) || $account->id == $loggedUser->id ;
    }

    public function store(Accounts $loggedUser){
        return $loggedUser->isAdmin() || $loggedUser->isDoctor();
    }
}
