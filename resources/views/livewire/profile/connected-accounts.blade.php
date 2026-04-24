<x-section-card
    title="Connected accounts"
    description="OAuth providers linked to your account. Use the matching provider's button on the sign-in page to add another."
>
    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
        @foreach ($providers as $provider)
            <li class="flex items-center justify-between py-3">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($provider) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ in_array($provider, $connectedProviders, true) ? 'Connected' : 'Not connected' }}
                    </p>
                </div>
                @if (in_array($provider, $connectedProviders, true))
                    <button type="button" wire:click="disconnect('{{ $provider }}')" class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300">
                        Disconnect
                    </button>
                @else
                    <a href="{{ route('socialite.redirect', ['provider' => $provider]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Connect
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</x-section-card>
