<x-layouts.app :title="$title ?? null">
    <main class="min-h-screen flex items-center justify-center px-4 py-12 bg-gray-50 dark:bg-gray-950">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white hover:text-gray-700 dark:hover:text-gray-300 transition">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="rounded-xl bg-white dark:bg-gray-900 p-8 shadow-lg ring-1 ring-gray-950/5 dark:ring-white/10">
                {{ $slot }}
            </div>
        </div>
    </main>
</x-layouts.app>
