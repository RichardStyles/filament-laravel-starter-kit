<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full bg-gray-100 dark:bg-gray-900"
    data-appearance="{{ auth()->user()?->appearance_preference ?? 'system' }}"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <script>
        (function () {
            const root = document.documentElement;
            const apply = (preference) => {
                const isDark = preference === 'dark'
                    || (preference === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                root.classList.toggle('dark', isDark);
                root.dataset.appearance = preference;
            };

            const stored = localStorage.getItem('appearance');
            apply(stored || root.dataset.appearance || 'system');

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if ((localStorage.getItem('appearance') || root.dataset.appearance) === 'system') {
                    apply('system');
                }
            });

            window.addEventListener('appearance-updated', (event) => {
                const preference = event.detail?.preference ?? event.detail?.[0]?.preference ?? 'system';
                localStorage.setItem('appearance', preference);
                apply(preference);
            });
        })();
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased">
    {{ $slot }}

    @livewire('notifications')
    @filamentScripts
</body>
</html>
