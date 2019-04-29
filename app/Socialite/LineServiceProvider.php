<?php

namespace App\Socialite;

use Laravel\Socialite\SocialiteServiceProvider;

class LineServiceProvider extends SocialiteServiceProvider
{
    public function register()
    {
        $this->app->singleton('Laravel\Socialite\Contracts\Factory',function($app){
            return new LineManager($app);
        });
    }
}