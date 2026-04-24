<x-profile.card
    title="Delete account"
    description="Once your account is deleted, all of its resources and data will be permanently removed."
    danger>

    @if (! $confirmingDeletion)
        <x-filament::button type="button" color="danger" wire:click="confirmDeletion">
            Delete account
        </x-filament::button>
    @else
        <form wire:submit="deleteAccount" class="space-y-4">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Enter your password to confirm permanent deletion. This cannot be undone.
            </p>

            {{ $this->form }}

            <div class="flex items-center gap-3">
                <x-filament::button type="submit" color="danger" wire:loading.attr="disabled" wire:target="deleteAccount">
                    <span wire:loading.remove wire:target="deleteAccount">Permanently delete</span>
                    <span wire:loading wire:target="deleteAccount">Deleting…</span>
                </x-filament::button>
                <x-filament::button type="button" color="gray" wire:click="$set('confirmingDeletion', false)">
                    Cancel
                </x-filament::button>
            </div>
        </form>
    @endif
</x-profile.card>
