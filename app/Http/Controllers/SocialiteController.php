<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends BaseController
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $userId = $user->getEmail();


        User::unguard();

        $resource = User::firstOrCreate(
            [
                // 'oauth_provider' => $provider,
                // 'oauth_id' => $user->getId(),
                'email' => $user->getEmail(),
            ],
            [
                'oauth_provider' => $provider,
                'oauth_id' => $user->getId(),

                'nickname' => $user->getNickname(),
                'first_name' => $user->getName(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar(),

                'email_verified_at' => now(),
                // 'remember_token' => Str::random(10),
                'password' => bcrypt(Str::random(32)),
            ],
        );

        User::reguard();

        Auth::login($resource, true);

        return redirect()->route('dashboard');
    }
}