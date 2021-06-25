<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $user      = User::where(['email' => $userSocial->getEmail()])->first();
        if ($user) {
            Auth::login($user);
            return redirect('/home');
        } else {
            $user = User::create([
                'name'        => $userSocial->getName(),
                'email'       => $userSocial->getEmail(),
                'image'       => $userSocial->getAvatar(),
                'provider_id' => $userSocial->getId(),
                'provider'    => $provider,
            ]);
            Auth::login($user);
            return redirect('/home');
        }
    }
}
