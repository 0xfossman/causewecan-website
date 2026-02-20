<?php

namespace App\Providers;

use App\Listeners\SocialiteDiscordExtendSocialite;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SocialiteWasCalled::class => [
            SocialiteDiscordExtendSocialite::class,
        ],
    ];
}
