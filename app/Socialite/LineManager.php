<?php

namespace App\Socialite;

use Laravel\Socialite\SocialiteManager;

class LineManager extends SocialiteManager{

    protected function createLineDriver()
    {
        $config = $this->app['config']['services.line'];

        return $this->buildProvider(
            'App\Socialite\LineProvider',$config
        );
    }
}