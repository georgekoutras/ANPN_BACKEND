<?php

namespace App\Providers;

use Illuminate\Mail\Mailer;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('company.mailer', function($app, $parameters){
            $smtpHost = Arr::get($parameters, 'host');
            $smtpPort = Arr::get($parameters, 'port');
            $smtpUsername = Arr::get($parameters, 'username');
            $smtpPassword = Arr::get($parameters, 'password');
            $smtpEncryption = Arr::get($parameters, 'encryption');

            $fromEmail = Arr::get($parameters, 'from_email');
            $fromName = Arr::get($parameters, 'from_name');

            $transport = new \Swift_SmtpTransport($smtpHost, $smtpPort);
            $transport->setUsername($smtpUsername);
            $transport->setPassword($smtpPassword);
            $transport->setEncryption($smtpEncryption);
            $swiftMailer = new \Swift_Mailer($transport);
            $mailer = new Mailer("Anapneo",$app->get('view'), $swiftMailer, $app->get('events'));
            $mailer->alwaysFrom($fromEmail, $fromName);

            return $mailer;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Paginator::useBootstrap();
        if (!defined('ADMIN')) {
            define('ADMIN', config('variables.APP_ADMIN', 'admin'));
        }
		//require_once base_path('resources/macros/form.php');
        Schema::defaultStringLength(191);

    }
}
