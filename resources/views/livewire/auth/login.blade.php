<div>
    <div class="mb-8 space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Sign in</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">New here?
            <a href="{{ route('register') }}" class="font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline">Create an account</a>
        </p>
    </div>

    <x-socialite-buttons />

    <form wire:submit="login">
        {{ $this->form }}

        <div class="mt-6 flex items-center justify-end">
            <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 underline">
                Forgot your password?
            </a>
        </div>

        <div class="mt-6">
            <x-filament::button
                type="submit"
                color="primary"
                size="lg"
                class="w-full"
                wire:loading.attr="disabled"
                wire:target="login">
                <span wire:loading.remove wire:target="login">Sign in</span>
                <span wire:loading wire:target="login">Signing in…</span>
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
