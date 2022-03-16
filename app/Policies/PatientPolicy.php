<?php

namespace App\Policies;

use App\Models\Accounts;
use App\Models\Patients;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    public function index(Accounts $account){
        return $account->isDoctor() || $account->isAdmin();
    }
}
