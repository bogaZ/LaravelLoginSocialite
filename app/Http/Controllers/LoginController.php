<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use TheSeer\Tokenizer\Exception;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'min:8'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('home')->with('success', 'Login Successfully');
        }


        return back()->with('loginError', 'Login is not successfully, please Check your Email & Password');

    }

    public function doRegister(Request $request)
    {


        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        $validate['password'] = bcrypt($request['password']);

        $login = User::create($validate);

        Auth::login($login);

        return redirect()->route('home');


    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended('login');
    }
}