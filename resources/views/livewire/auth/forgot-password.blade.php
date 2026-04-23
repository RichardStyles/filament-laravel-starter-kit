<div>
    <div class="mb-6 space-y-1">
        <h1 class="text-xl font-semibold tracking-tight">Reset your password</h1>
        <p class="text-sm text-neutral-600">
            Enter your email and we'll send you a link to set a new password.
        </p>
    </div>

    <form wire:submit="sendResetLink" class="space-y-4">
        {{ $this->form }}

        <button type="submit"
            class="inline-flex w-full items-center justify-center rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2"
            wire:loading.attr="disabled"
            wire:target="sendResetLink">
            <span wire:loading.remove wire:target="sendResetLink">Send reset link</span>
            <span wire:loading wire:target="sendResetLink">Sending…</span>
        </button>

        <p class="text-center text-sm text-neutral-600">
            <a href="{{ route('login') }}" class="text-neutral-900 underline-offset-4 hover:underline">Back to sign in</a>
        </p>
    </form>

    <x-filament-actions::modals />
</div>
