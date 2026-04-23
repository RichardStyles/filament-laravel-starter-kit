<div>
    <div class="mb-8 space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Verify your email</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Before continuing, please click the verification link we just emailed to
            <span class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->email }}</span>.
            If you didn't get it, we can send another.
        </p>
    </div>

    <div class="space-y-4">
        <x-filament::button
            wire:click="resend"
            color="primary"
            size="lg"
            class="w-full"
            wire:loading.attr="disabled"
            wire:target="resend">
            <span wire:loading.remove wire:target="resend">Resend verification email</span>
            <span wire:loading wire:target="resend">Sending…</span>
        </x-filament::button>

        <x-filament::button
            wire:click="logout"
            color="gray"
            outlined
            size="lg"
            class="w-full">
            Log out
        </x-filament::button>
    </div>

    <x-filament-actions::modals />
</div>
