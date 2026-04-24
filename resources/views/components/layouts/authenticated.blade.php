<x-layouts.app :title="$title ?? null">
    <div class="min-h-full">
        @livewire('navigation')

        <div class="py-10">
            @isset($header)
                <header>
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.app>
