@props([
    'title',
    'description' => null,
    'danger' => false,
])

@php
    $ringClasses = $danger
        ? 'ring-danger-600/20 dark:ring-danger-500/30'
        : 'ring-gray-950/5 dark:ring-white/10';

    $titleClasses = $danger
        ? 'text-danger-700 dark:text-danger-400'
        : 'text-gray-900 dark:text-white';
@endphp

<section {{ $attributes->merge(['class' => 'grid gap-x-8 gap-y-6 rounded-xl bg-white p-6 shadow-sm ring-1 sm:p-8 dark:bg-gray-800/50 md:grid-cols-3 '.$ringClasses]) }}>
    <header class="space-y-1 md:col-span-1">
        <h2 class="text-lg font-semibold tracking-tight {{ $titleClasses }}">
            {{ $title }}
        </h2>
        @if ($description)
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
        @endif
    </header>

    <div class="md:col-span-2">
        {{ $slot }}
    </div>
</section>
