<div>
    <div class="mb-6 space-y-2">
        <h1 class="text-xl font-semibold tracking-tight">Verify your email</h1>
        <p class="text-sm text-neutral-600">
            Before continuing, please click the verification link we just emailed to
            <span class="font-medium text-neutral-900">{{ auth()->user()->email }}</span>.
            If you didn't get it, we can send another.
        </p>
    </div>

    <div class="space-y-3">
        <button wire:click="resend"
            class="inline-flex w-full items-center justify-center rounded-lg bg-neutral-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2"
            wire:loading.attr="disabled"
            wire:target="resend">
            <span wire:loading.remove wire:target="resend">Resend verification email</span>
            <span wire:loading wire:target="resend">Sending…</span>
        </button>

        <button wire:click="logout"
            class="inline-flex w-full items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-neutral-700 ring-1 ring-neutral-300 hover:bg-neutral-50">
            Log out
        </button>
    </div>
</div>
