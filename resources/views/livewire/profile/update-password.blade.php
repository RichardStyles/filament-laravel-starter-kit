<x-section-card
    title="Update password"
    description="Choose a long, strong password to keep your account secure.">

    <form wire:submit="updatePassword" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button
                type="submit"
                color="primary"
                wire:loading.attr="disabled"
                wire:target="updatePassword">
                <span wire:loading.remove wire:target="updatePassword">Save</span>
                <span wire:loading wire:target="updatePassword">Saving…</span>
            </x-filament::button>
        </div>
    </form>
</x-section-card>
