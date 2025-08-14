<x-guest-layout>
    <section class="h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <x-logotipo></x-logotipo>
            <div
                class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        {{ __('Verify your email address') }}
                        <div class="mb-4 mt-2 font-normal text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Before continuing, please verify your email address by clicking on the link we emailed to you. If you didn\'t receive the email, we can send another.') }}
                        </div>
                    </h1>
                    <div class="space-y-6">
                        @if (session('status') == 'verification-link-sent')
                            <div
                                class="p-4 mb-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 flex items-start">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fa-solid fa-circle-check text-green-500 dark:text-green-400 text-xl mr-3 mt-0.5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-green-800 dark:text-green-100">
                                        {{ __('Verification sent!') }}</h3>
                                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                                        {{ __('We have sent a new verification link to your email address. Please check your inbox.') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <x-button class="w-full font-bold py-3.5 px-4 shadow-2xl">
                                <i class="fa-solid fa-paper-plane mr-2"></i>
                                {{ __('Resend verification email') }}
                            </x-button>
                        </form>
                        <div class="relative flex items-center py-2">
                            <div
                                class="flex-grow border-t border-gray-300 dark:border-gray-600 transition-all duration-300">
                            </div>
                            <span
                                class="flex-shrink mx-4 text-gray-500 dark:text-gray-400 text-sm transform transition-transform hover:scale-110">{{ __('Other options') }}</span>
                            <div
                                class="flex-grow border-t border-gray-300 dark:border-gray-600 transition-all duration-300">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center justify-center gap-2 p-3.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:border-indigo-300 dark:hover:border-indigo-500 group">
                                <i
                                    class="fa-solid fa-user-pen text-indigo-500 dark:text-indigo-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors"></i>
                                <span
                                    class="group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ __('Edit profile') }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 p-3.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:border-red-300 dark:hover:border-red-500 group">
                                    <i
                                        class="fa-solid fa-arrow-right-from-bracket text-red-500 dark:text-red-400 group-hover:text-red-600 dark:group-hover:text-red-300 transition-colors"></i>
                                    <span
                                        class="group-hover:text-red-600 dark:group-hover:text-red-300 transition-colors">{{ __('Log out') }}</span>
                                </button>
                            </form>
                        </div>
                        <div class="text-center pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-info mr-1.5"></i>
                                {{ __('If you don\'t see the email, check your spam folder.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        async function checkVerificationStatus() {
            try {
                const response = await fetch('{{ route('verification.status') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.verified) {
                    window.location.href = "{{ route('dashboard') }}";
                }
            } catch (error) {
                console.error("Error checking verification status:", error);
            }
        }

        setInterval(checkVerificationStatus, 5000);
    </script>
</x-guest-layout>
