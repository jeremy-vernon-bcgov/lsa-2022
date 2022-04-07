<?php

namespace App\Providers;

use App\Models\Recipient;
use App\Policies\AwardPolicy;
use App\Policies\RecipientPolicy;
use App\Policies\CeremonyPolicy;
use App\Policies\AttendeePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Award::class => AwardPolicy::class,
        Recipient::class => RecipientPolicy::class,
        Ceremony::class => CeremonyPolicy::class,
        Attendee::class => AttendeePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

    }
}
