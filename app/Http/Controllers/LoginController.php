<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialiteUser = Socialite::driver($provider)->user();
        // dd($socialiteUser);
        $user = User::firstOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId()
            ],
            [
                'email' => $socialiteUser->getEmail(),
                'name' => $socialiteUser->getName(),
            ]
        );
        // log the user in
        auth()->login($user, false);

        // redirect dashboard
        return redirect('dashboard');
    }
}
