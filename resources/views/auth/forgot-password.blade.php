<x-guest-layout>
    <section class="h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <x-logotipo></x-logotipo>
            <div
                class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        {{ __('Forgot your password?') }}
                        <div class="mb-4 mt-2 font-normal text-sm text-gray-600 dark:text-gray-400">
                            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>
                    </h1>
                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ $value }}
                        </div>
                    @endsession
                    <x-validation-errors class="mb-4" />
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="block">
                            <x-label for="email">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                {{ __('Email') }}
                            </x-label>
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username"
                                placeholder="{{ __('name@stargroup.com.mx') }}" />
                        </div>
                        <x-button class="w-full mt-6 font-bold">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            {{ __('Email Password Reset Link') }}
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
