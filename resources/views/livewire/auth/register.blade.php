<div>
    <div class="mb-8 space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Create your account</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Already have one?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline">Sign in</a>
        </p>
    </div>

    <form wire:submit="register">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button
                type="submit"
                color="primary"
                size="lg"
                class="w-full"
                wire:loading.attr="disabled"
                wire:target="register">
                <span wire:loading.remove wire:target="register">Create account</span>
                <span wire:loading wire:target="register">Creating…</span>
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
