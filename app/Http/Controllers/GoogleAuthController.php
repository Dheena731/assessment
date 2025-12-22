<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

        
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Access denied. Contact admin.');
            }

           
            Auth::login($user);

            return $user->role === 'super_admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('resume.upload');

        } catch (Exception $e) {

            // 🔍 TEMPORARY DEBUG (VERY IMPORTANT)
            dd($e->getMessage());

            // return redirect()->route('login')
            //     ->with('error', 'Google login failed.');
        }
    }
}
