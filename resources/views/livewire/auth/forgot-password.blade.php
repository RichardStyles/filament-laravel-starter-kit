<div>
    <div class="mb-8 space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Reset your password</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Enter your email and we'll send you a link to set a new password.
        </p>
    </div>

    <form wire:submit="sendResetLink">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button
                type="submit"
                color="primary"
                size="lg"
                class="w-full"
                wire:loading.attr="disabled"
                wire:target="sendResetLink">
                <span wire:loading.remove wire:target="sendResetLink">Send reset link</span>
                <span wire:loading wire:target="sendResetLink">Sending…</span>
            </x-filament::button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('login') }}" class="text-gray-900 dark:text-gray-100 underline hover:no-underline">Back to sign in</a>
        </p>
    </form>

    <x-filament-actions::modals />
</div>
