<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use TheSeer\Tokenizer\Exception;



class SocialiteController extends Controller
{
    //
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();

            $data = User::all()->firstWhere('google_id', $socialiteUser->id);

            if (!empty($data)) {
                Auth::login($data);
                return redirect()->route('home');
            }

            $user = User::create(
                [
                    'name' => $socialiteUser->name,
                    'email' => $socialiteUser->email,
                    $provider . '_id' => $socialiteUser->id,
                ]
            );

            Auth::login($user);

            return redirect()->route('home');


        } catch (\Exception $e) {
            report($e);

            return redirect()->route('login')->with('loginError', 'Login is not successfully, please Check your Email & Password');
        }
    }
}