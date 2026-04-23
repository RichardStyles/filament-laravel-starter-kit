<div>
    <div class="mb-6 space-y-1">
        <h1 class="text-xl font-semibold tracking-tight">Sign in</h1>
        <p class="text-sm text-neutral-600">New here?
            <a href="{{ route('register') }}" class="font-medium text-neutral-900 underline-offset-4 hover:underline">Create an account</a>.
        </p>
    </div>

    <form wire:submit="login" class="space-y-4">
        {{ $this->form }}

        <div class="flex items-center justify-between text-sm">
            <a href="{{ route('password.request') }}" class="text-neutral-700 underline-offset-4 hover:underline">
                Forgot your password?
            </a>
        </div>

        <button type="submit"
            class="inline-flex w-full items-center justify-center rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2"
            wire:loading.attr="disabled"
            wire:target="login">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in…</span>
        </button>
    </form>
</div>
