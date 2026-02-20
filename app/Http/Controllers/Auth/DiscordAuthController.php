<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('discord')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $discordUser = Socialite::driver('discord')->stateless()->user();

        $user = User::query()->updateOrCreate(
            ['discord_id' => $discordUser->getId()],
            [
                'name' => $discordUser->getName() ?? $discordUser->getNickname() ?? 'Discord User',
                'email' => $discordUser->getEmail(),
                'avatar_url' => $discordUser->getAvatar(),
            ]
        );

        Auth::login($user, true);

        return redirect()->route('home');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
