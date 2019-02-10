<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Platform;
use App\Models\Profile;
use App\Models\Review;
use App\Models\User;
use App\Policies\AnswerPolicy;
use App\Policies\PlatformPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;
use Dingo\Api\Auth\Auth;
use Dingo\Api\Auth\Provider\JWT;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\JWTAuth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });


        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Profile::class, ProfilePolicy::class);
        Gate::policy(Platform::class, PlatformPolicy::class);
        Gate::policy(Answer::class, AnswerPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);

        Gate::define('view', function($user) {
            return ($user->profile_id===2);
        });
    }
}
