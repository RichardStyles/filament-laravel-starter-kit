<x-layouts.app :title="$title ?? null">
    <main class="min-h-screen grid place-items-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="{{ url('/') }}" class="text-xl font-semibold tracking-tight">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-neutral-200">
                {{ $slot }}
            </div>
        </div>
    </main>
</x-layouts.app>
