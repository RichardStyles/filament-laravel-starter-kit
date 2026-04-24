@php
    $providers = config('services.socialite.providers');
    $labels = ['github' => 'Sign in with GitHub', 'google' => 'Sign in with Google'];
    $iconPaths = [
        'github' => 'M12 .5C5.7.5.5 5.7.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.2.8-.6v-2c-3.2.7-3.9-1.5-3.9-1.5-.5-1.4-1.3-1.7-1.3-1.7-1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1.1 1.8 2.8 1.3 3.5 1 .1-.8.4-1.3.7-1.6-2.6-.3-5.3-1.3-5.3-5.7 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.2 0 0 1-.3 3.3 1.2 1-.3 2-.4 3-.4s2 .1 3 .4c2.3-1.5 3.3-1.2 3.3-1.2.6 1.7.2 2.9.1 3.2.7.8 1.2 1.8 1.2 3.1 0 4.4-2.7 5.4-5.3 5.7.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6 4.6-1.5 7.9-5.8 7.9-10.9C23.5 5.7 18.3.5 12 .5z',
        'google' => 'M21.35 11.1H12v2.92h5.39c-.23 1.49-1.61 4.36-5.39 4.36-3.24 0-5.89-2.69-5.89-6s2.65-6 5.89-6c1.85 0 3.09.79 3.8 1.47l2.59-2.49C16.78 3.92 14.62 3 12 3 6.92 3 2.79 7.13 2.79 12.21S6.92 21.42 12 21.42c6.93 0 9.6-4.86 9.6-7.4 0-.5-.05-.88-.12-1.27z',
    ];
@endphp

@if (! empty($providers))
    <div class="space-y-3">
        @foreach ($providers as $provider)
            <a
                href="{{ route('socialite.redirect', ['provider' => $provider]) }}"
                class="flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:outline-indigo-500"
            >
                @if (isset($iconPaths[$provider]))
                    <svg viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true">
                        <path d="{{ $iconPaths[$provider] }}" />
                    </svg>
                @endif
                {{ $labels[$provider] ?? 'Sign in with '.ucfirst($provider) }}
            </a>
        @endforeach

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200 dark:border-white/10"></div></div>
            <div class="relative flex justify-center text-xs"><span class="bg-white px-2 text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">or continue with email</span></div>
        </div>
    </div>
@endif
