<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use Carbon\Carbon;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register routes for Passport
        // Passport::routes();

        // Set custom token expiration
        Passport::tokensExpireIn(Carbon::now()->addMinutes(10000)); // Access token expires in 15 minutes
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(20)); // Refresh token expires in 30 days

        // Optional: Set expiration for personal access tokens
        Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(6));
    }
}
