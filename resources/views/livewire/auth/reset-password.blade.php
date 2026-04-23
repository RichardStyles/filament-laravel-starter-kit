<div>
    <div class="mb-6 space-y-1">
        <h1 class="text-xl font-semibold tracking-tight">Set a new password</h1>
        <p class="text-sm text-neutral-600">
            Choose something strong — at least 8 characters.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        {{ $this->form }}

        <button type="submit"
            class="inline-flex w-full items-center justify-center rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2"
            wire:loading.attr="disabled"
            wire:target="resetPassword">
            <span wire:loading.remove wire:target="resetPassword">Reset password</span>
            <span wire:loading wire:target="resetPassword">Resetting…</span>
        </button>
    </form>

    <x-filament-actions::modals />
</div>
