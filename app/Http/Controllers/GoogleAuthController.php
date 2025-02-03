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
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => __('¡Access denied!'),
                    'text' => __('Only emails with the @stargroup.com.mx domain are allowed.')
                ]);

                return redirect('/login');
            }

            $user = User::updateOrCreate(
                ['google_id' => $user_google->id],
                [
                    'name' => $user_google->name,
                    'email' => $email,
                ]
            );

            Auth::login($user);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => __('Oops!'),
                'text' => __('An error occurred during Google authentication.')
            ]);

            return redirect('/login');
        }
    }
}
