<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/11574/11574267.png">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/de19704bb3.js" crossorigin="anonymous"></script>

    <!-- Dark Mode -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        {{ $slot }}
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts

    @if (session('swal'))
        <script>
            window.onload = functio n() {
                Swal.fire({
                    icon: '{{ session('swal')['icon'] }}',
                    title: '{{ session('swal')['title'] }}',
                    text: '{{ session('swal')['text'] ?? '' }}'
                });
            };
        </script>
    @endif

    @if (session('swal'))
        <script>
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif
</body>

</html>
