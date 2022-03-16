<?php

namespace App\Providers;

use App\Auth\AccessTokenGuard;
use App\Auth\TokenToUserProvider;
use App\Models\Accounts;
use App\Models\Cats;
use App\Models\Ccis;
use App\Models\Ccqs;
use App\Models\DailyReport;
use App\Models\Deaths;
use App\Models\Notifications;
use App\Models\Patients;
use App\Models\Reading;
use App\Models\Treatments;
use App\Policies\AccountPolicy;
use App\Policies\CatsPolicy;
use App\Policies\CciPolicy;
use App\Policies\CcqPolicy;
use App\Policies\DailyReportPolicy;
use App\Policies\DeathPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PatientPolicy;
use App\Policies\ReadingPolicy;
use App\Policies\TreatmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Patients::class => PatientPolicy::class,
        DailyReport::class => DailyReportPolicy::class,
        Notifications::class => NotificationPolicy::class,
        Accounts::class => AccountPolicy::class,
        Cats::class => CatsPolicy::class,
        Ccqs::class => CcqPolicy::class,
        Ccis::class => CciPolicy::class,
        Reading::class => ReadingPolicy::class,
        Treatments::class => TreatmentPolicy::class,
        Deaths::class => DeathPolicy::class,
        ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('stateless_guard', function ($app, $name, array $config) {

            $userProvider = app(TokenToUserProvider::class);
            $request = app('request');

            return new AccessTokenGuard($userProvider, $request, $config);
        });
    }
}
