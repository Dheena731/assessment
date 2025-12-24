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
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // ✅ Auto-register or fetch user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'role' => 'user',
                    'password' => null,
                ]
            );

            Auth::login($user);
            request()->session()->regenerate();

            // ✅ Role-based redirect
            return $user->role === 'super_admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('resume.upload');

        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }
}
