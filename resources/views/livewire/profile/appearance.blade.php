<x-section-card
    title="Appearance"
    description="Choose how the interface looks. System tracks your OS preference and switches automatically."
>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button
                type="submit"
                color="primary"
                wire:loading.attr="disabled"
                wire:target="save">
                <span wire:loading.remove wire:target="save">Save appearance</span>
                <span wire:loading wire:target="save">Saving…</span>
            </x-filament::button>
        </div>
    </form>
</x-section-card>
