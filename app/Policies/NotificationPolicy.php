<?php

namespace App\Policies;

use App\Models\Accounts;
use App\Models\Notifications;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    public function indexMobile(Accounts $loggedUser, Accounts $account){
        return $loggedUser->isAdmin() || ($loggedUser->isDoctor() && $loggedUser->hasPatient($account->patientInfo)) || $account->id == $loggedUser->id ;
    }

    public function index(Accounts $loggedUser){
        return $loggedUser->isAdmin() || $loggedUser->isDoctor();
    }

    public function accountNotifications(Accounts $loggedUser, Accounts $account){
        return $loggedUser->isAdmin() || ($loggedUser->isDoctor() && $loggedUser->hasPatient($account->patientInfo)) || $account->id == $loggedUser->id ;
    }
}
