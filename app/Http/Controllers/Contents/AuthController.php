<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin(){
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->where('status', 'active')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['message' => 'Invalid credentials']);
        }

        Auth::login($user);

        if(Auth::user()->position == 'admin'){
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        } elseif(Auth::user()->position == 'clerk'){
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // return redirect()->route('users')->with('success', 'Login successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
