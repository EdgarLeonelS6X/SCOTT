<x-guest-layout>
    <section class="h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <x-logotipo></x-logotipo>
            <div
                class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-4 sm:p-8">
                    @session('status')
                        <div class="mb-1 font-medium text-sm text-green-600 dark:text-green-400">
                            <p class="flex justify-center items-center text-sm text-green-700 dark:text-green-300">
                                <i class="fa-solid fa-circle-check text-green-500 dark:text-green-400 text-lg mr-3"></i>
                                {{ $value }}
                            </p>
                        </div>
                    @endsession
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        {{ __('Sign in to your account') }}
                    </h1>
                    @if (session('swal'))
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: '{{ session('swal')['icon'] }}',
                                    title: '{{ session('swal')['title'] }}',
                                    text: '{{ session('swal')['text'] }}'
                                });
                            };
                        </script>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <x-label for="email">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                {{ __('Email') }}
                            </x-label>
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username"
                                placeholder="{{ __('name@stargroup.com.mx') }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="password">
                                <i class="fa-solid fa-key mr-1"></i>
                                {{ __('Password') }}
                            </x-label>
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <x-checkbox id="remember_me" name="remember" />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember_me" class="flex items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Remember me') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-primary-500 underline hover:text-primary-600"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        <x-button class="w-full mt-6 flex items-center justify-center shadow font-bold">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i>
                            {{ __('Log in') }}
                        </x-button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-4 flex justify-center">
                            {{ __("Don't have an account yet?") }}
                            <a href="{{ route('register') }}"
                                class="ms-2 font-medium text-primary-500 underline hover:text-primary-600">
                                {{ __('Sign up') }}
                            </a>
                        </p>
                    </form>
                    <div class="inline-flex items-center justify-center w-full">
                        <hr class="w-full h-px bg-gray-200 border-0 dark:bg-gray-700">
                        <span
                            class="absolute px-3 font-medium text-gray-900 -translate-x-1/2 bg-white left-1/2 dark:text-white dark:bg-gray-800">
                            {{ __('Or') }}
                        </span>
                    </div>
                    <a class="cursor-pointer flex items-center justify-center w-full text-gray-700 bg-transparent border 
                        border-gray-400 hover:bg-gray-100 hover:text-gray-900 focus:ring-4 focus:outline-none 
                        focus:ring-gray-300 font-bold rounded-lg text-base px-5 py-2.5 text-center me-2 mb-2 shadow
                        dark:text-white dark:bg-transparent dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-500"
                        onclick="openGooglePopup()">
                        <i class="fa-brands fa-google mr-2"></i>
                        {{ __('Log in with Google') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

<script>
    function openGooglePopup() {
        let width = 600,
            height = 500;
        let left = window.innerWidth / 2 - width / 2 + window.screenX;
        let top = window.innerHeight / 2 - height / 2 + window.screenY;

        let popup = window.open("{{ route('redirectToGoogle') }}", "Google Login",
            `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`);

        if (popup) {
            popup.focus();
        } else {
            alert("Popup blocked! Please allow popups for this site.");
        }
    }

    window.addEventListener("message", function(event) {
        if (event.data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: event.data.error
            });
        } else if (event.data.success) {
            location.reload();
        }
    });
</script>
