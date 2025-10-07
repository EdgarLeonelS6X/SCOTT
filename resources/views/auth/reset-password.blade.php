<x-guest-layout>
    <section class="h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <x-logotipo></x-logotipo>
            <div
                class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        {{ __('Reset your password') }}
                    </h1>
                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ $value }}
                        </div>
                    @endsession
                    <x-validation-errors class="mb-4 text-center" />
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $request->email }}">
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="block">
                            <x-label for="email">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                {{ __('Email') }}
                            </x-label>
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $request->email)" required autofocus autocomplete="username"
                                placeholder="{{ __('name@stargroup.com.mx') }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="password">
                                <i class="fa-solid fa-key mr-1"></i>
                                {{ __('Password') }}
                            </x-label>
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                                autocomplete="new-password" placeholder="••••••••" />
                        </div>
                        <div class="mt-4">
                            <x-label for="password_confirmation">
                                <i class="fa-solid fa-key mr-1"></i>
                                {{ __('Confirm Password') }}
                            </x-label>
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="••••••••" />
                        </div>
                        <x-button class="w-full mt-8 font-bold py-3.5 px-4 shadow-2xl">
                            <i class="fa-solid fa-circle-check mr-2"></i>
                            {{ __('Reset password') }}
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
