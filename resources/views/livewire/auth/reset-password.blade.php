<div>
    <div class="mb-8 space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Set a new password</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Choose something strong — at least 8 characters.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-6">
        {{ $this->form }}

        <x-filament::button
            type="submit"
            color="primary"
            size="lg"
            class="w-full"
            wire:loading.attr="disabled"
            wire:target="resetPassword">
            <span wire:loading.remove wire:target="resetPassword">Reset password</span>
            <span wire:loading wire:target="resetPassword">Resetting…</span>
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
