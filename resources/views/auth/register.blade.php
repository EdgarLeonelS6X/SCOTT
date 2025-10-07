<x-guest-layout>
    <section class="h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="mb-2">
                <x-logotipo></x-logotipo>
            </div>
            <div
                class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 sm:max-w-4xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <a class="cursor-pointer flex items-center justify-center w-full text-gray-700 bg-transparent border
                            border-gray-400 hover:bg-gray-100 hover:text-gray-900 focus:ring-4 focus:outline-none
                            focus:ring-gray-300 font-bold rounded-lg text-base px-5 py-2.5 text-center me-2 mb-2 shadow
                            dark:text-white dark:bg-transparent dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-500"
                        onclick="openGooglePopup()">
                        <i class="fa-brands fa-google mr-2"></i>
                        {{ __('Sign up with Google') }}
                    </a>
                    <div class="inline-flex items-center justify-center w-full">
                        <hr class="w-full h-px bg-gray-200 border-0 dark:bg-gray-700">
                        <span
                            class="absolute px-3 font-medium text-gray-900 -translate-x-1/2 bg-white left-1/2 dark:text-white dark:bg-gray-800">
                            {{ __('Or') }}
                        </span>
                    </div>
                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ $value }}
                        </div>
                    @endsession
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div>
                            <x-label for="name">
                                <i class="fa-solid fa-user mr-1"></i>
                                {{ __('Name') }}
                            </x-label>
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name"
                                placeholder="{{ __('ex. Edgar Leonel Acevedo Cuevas') }}" />
                        </div>
                        <div class="mt-4">
                            <x-label for="email">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                {{ __('Email') }}
                            </x-label>
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username"
                                placeholder="{{ __('name@stargroup.com.mx') }}" />
                        </div>
                        <div class="grid grid-cols-1 mt-4 sm:grid-cols-2 gap-4">
                            <div>
                                <x-label for="password">
                                    <i class="fa-solid fa-key mr-1"></i>
                                    {{ __('Password') }}
                                </x-label>
                                <x-input id="password" class="block mt-1 w-full" type="password" name="password"
                                    required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                            <div>
                                <x-label for="password_confirmation">
                                    <i class="fa-solid fa-key mr-1"></i>
                                    {{ __('Confirm Password') }}
                                </x-label>
                                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="••••••••" />
                            </div>
                        </div>
                        <x-button class="w-full mt-8 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i>
                            {{ __('Sign up') }}
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

<script>
    function openGooglePopup() {
        let width = 600,
            height = 500;
        let left = (screen.width - width) / 2;
        let top = (screen.height - height) / 2;

        let popup = window.open("{{ route('redirectToGoogle') }}", "Google Login",
            `width=${width},height=${height},top=${top},left=${left}`);
    }

    window.addEventListener("message", function(event) {
        if (event.data.error) {
            Swal.fire({
                    icon: '{{ session('swal')['icon'] ?? '' }}',
                    title: '{{ session('swal')['title'] ?? '' }}',
                    text: '{{ session('swal')['text'] ?? '' }}'
            });
        } else if (event.data.success) {
            location.reload();
        }
    });
</script>
