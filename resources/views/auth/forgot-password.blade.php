<x-guest-layout>
    <section class="h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <x-logotipo></x-logotipo>
            <div
                class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        {{ __('Forgot your password?') }}
                        <div class="mb-4 mt-2 font-normal text-sm text-gray-600 dark:text-gray-400">
                            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>
                    </h1>
                    @session('status')
                        <div
                            class="p-4 mb-2 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 flex items-start">
                            <div class="flex-shrink-0">
                                <i
                                    class="fa-solid fa-circle-check text-green-500 dark:text-green-400 text-xl mr-3 mt-0.5"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-green-800 dark:text-green-100">
                                    {{ __('Reset link sent!') }}</h3>
                                <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                                    {{ $value }}
                                </p>
                            </div>
                        </div>
                    @endsession
                    <x-validation-errors class="w-full text-center mb-4" />
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf
                        <div class="block">
                            <x-label for="email" class="flex items-center gap-2">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                <span>{{ __('Email address') }}</span>
                            </x-label>
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username"
                                placeholder="{{ __('name@stargroup.com.mx') }}" />
                        </div>
                        <x-button class="w-full font-bold py-3.5 px-4 shadow-2xl">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            {{ __('Email password reset link') }}
                        </x-button>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-info mr-1.5"></i>
                                {{ __('If you don\'t see the email, check your spam folder.') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
