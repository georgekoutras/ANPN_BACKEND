<?php

namespace App\Policies;

use App\Models\Accounts;
use App\Models\DailyReport;
use App\Models\Patients;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyReportPolicy
{
    use HandlesAuthorization;

    public function index(Accounts $account){
        return $account->isAdmin() || $account->isDoctor();
    }

    public function allReports(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient)) || $account->id == $patient->account_id;
    }

    public function view(Accounts $account,Patients $patient, DailyReport $dailyReport){
        return $dailyReport->patient_id == $patient->id
            && ($account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient)) || $account->id == $patient->account_id);
    }

    public function store(Accounts $account,Patients $patient){
        return $account->isAdmin() || ($account->isDoctor() && $account->hasPatient($patient)) || $account->id == $patient->account_id;
    }

    public function delete(Accounts $account){
        return $account->isAdmin();
    }

}
