<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use App\Actions\Fortify\ResetUserPassword;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use App\Actions\Fortify\UpdateUserPassword;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ResetsUserPasswords::class, ResetUserPassword::class);
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);
        $this->app->singleton(UpdatesUserProfileInformation::class, UpdateUserProfileInformation::class);
        $this->app->singleton(UpdatesUserPasswords::class, UpdateUserPassword::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($credentials, [
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());

                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' =>  $errors,
                ]);

                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => __('The provided credentials are incorrect.'),
                ]);

                throw ValidationException::withMessages([
                    'email' => __('The provided credentials are incorrect.'),
                ]);
            }

            return $user;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
