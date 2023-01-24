<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;



class SocialiteController extends Controller
{
    //
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialiteUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate(
            [
                'name' => $socialiteUser->name,
                'email' => $socialiteUser->email,
                $provider . '_id' => $socialiteUser->id,
            ]
        );

        Auth::login($user);
        return redirect()->route('home');
    }
}