<?php

namespace App\Listeners;

use SocialiteProviders\Discord\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class SocialiteDiscordExtendSocialite
{
    public function handle(SocialiteWasCalled $event): void
    {
        $event->extendSocialite('discord', Provider::class);
    }
}
