<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user_google = Socialite::driver('google')->stateless()->user();
            $email = $user_google->email;

            if (!str_ends_with($email, '@stargroup.com.mx')) {
                return "<script>
                window.opener.postMessage({ error: 'Only emails with the @stargroup.com.mx domain are allowed.' }, '*');
                window.close();
            </script>";
            }

            $user = User::updateOrCreate(
                ['google_id' => $user_google->id],
                [
                    'name' => $user_google->name,
                    'email' => $email,
                ]
            );

            Auth::login($user);

            return "<script>
            window.opener.postMessage({ success: true }, '*');
            window.close();
        </script>";
        } catch (\Exception $e) {
            return "<script>
            window.opener.postMessage({ error: 'An error occurred during Google authentication: " . $e->getMessage() . "' }, '*');
            window.close();
        </script>";
        }
    }
}
