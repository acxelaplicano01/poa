<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') g}}</title>

    <script>
        (() => {
            const darkStored = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (darkStored === 'true' || (!darkStored && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>


   <script src="{{ asset('js/rk/notificactions.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/rk/rk.css') }}">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="bg-white dark:bg-zinc-900 text-black dark:text-white transition-colors duration-200">
    {{ $slot }}
    <div id="notification-container" class="fixed inset-0 z-50 pointer-events-none hidden flex flex-col gap-2 p-4">
    </div>
</body>

</html>
