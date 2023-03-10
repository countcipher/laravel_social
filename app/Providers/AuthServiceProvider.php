<?php

namespace App\Providers;

 use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class //This policy was added in.  It is the PostPolicy found in the Policies folder. --count cipher
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //This gate was added in; be sure to uncomment out the Gate facade at the top -- Count Cipher
        Gate::define('visitAdminPages', function($user){
            return $user->isAdmin === 1;
        });

        //
    }
}
