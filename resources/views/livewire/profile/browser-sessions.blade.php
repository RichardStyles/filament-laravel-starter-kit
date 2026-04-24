<x-section-card
    title="Browser sessions"
    description="Manage the browsers where you're currently signed in.">

    <div class="mb-6 divide-y divide-gray-100 dark:divide-white/10">
        @forelse ($this->sessions as $session)
            <div class="flex flex-wrap items-center justify-between gap-3 py-3 text-sm">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $session->agent['platform'] }} · {{ $session->agent['browser'] }}
                    </p>
                    <p class="text-gray-500 dark:text-gray-400">
                        {{ $session->ip_address }} · last active {{ $session->last_active }}
                    </p>
                </div>
                @if ($session->is_current_device)
                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
                        This device
                    </span>
                @endif
            </div>
        @empty
            <p class="py-3 text-sm text-gray-500 dark:text-gray-400">No active sessions.</p>
        @endforelse
    </div>

    @if ($confirmingLogout)
        <form wire:submit="logoutOtherBrowserSessions" class="space-y-4">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Enter your password to confirm logging out of all other browser sessions.
            </p>

            {{ $this->form }}

            <div class="flex items-center gap-3">
                <x-filament::button type="submit" color="danger" wire:loading.attr="disabled" wire:target="logoutOtherBrowserSessions">
                    <span wire:loading.remove wire:target="logoutOtherBrowserSessions">Log out other sessions</span>
                    <span wire:loading wire:target="logoutOtherBrowserSessions">Logging out…</span>
                </x-filament::button>
                <x-filament::button type="button" color="gray" wire:click="$set('confirmingLogout', false)">
                    Cancel
                </x-filament::button>
            </div>
        </form>
    @else
        <x-filament::button type="button" color="danger" wire:click="$set('confirmingLogout', true)">
            Log out other browser sessions
        </x-filament::button>
    @endif
</x-section-card>
