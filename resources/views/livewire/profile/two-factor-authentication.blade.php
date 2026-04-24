@php
    $user = auth()->user()->fresh();
    $hasSecret = $user->two_factor_secret !== null;
    $isConfirmed = $hasSecret && $user->two_factor_confirmed_at !== null;
    $isPending = $hasSecret && ! $isConfirmed;
@endphp

<x-section-card
    title="Two-factor authentication"
    description="Add an extra layer of security using a time-based one-time password.">

    @if (! $hasSecret)
        <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
            You have not enabled two-factor authentication.
        </p>
        <x-filament::button
            type="button"
            color="primary"
            wire:click="enableTwoFactorAuthentication"
            wire:loading.attr="disabled"
            wire:target="enableTwoFactorAuthentication">
            <span wire:loading.remove wire:target="enableTwoFactorAuthentication">Enable</span>
            <span wire:loading wire:target="enableTwoFactorAuthentication">Enabling…</span>
        </x-filament::button>
    @elseif ($isPending)
        <div class="grid gap-6 sm:grid-cols-[auto_minmax(0,1fr)] sm:items-start">
            <div class="inline-block rounded bg-white p-3 ring-1 ring-gray-200">
                {!! $user->twoFactorQrCodeSvg() !!}
            </div>

            <div class="space-y-4">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Scan the QR code with your authenticator app, then enter the generated six-digit code.
                </p>

                <details class="text-sm text-gray-600 dark:text-gray-400">
                    <summary class="cursor-pointer select-none">Can't scan the code? Show setup key</summary>
                    <code class="mt-2 block break-all rounded bg-gray-100 p-2 font-mono text-xs dark:bg-gray-900">{{ decrypt($user->two_factor_secret) }}</code>
                </details>

                <form wire:submit="confirmTwoFactorAuthentication" class="space-y-4">
                    {{ $this->form }}
                    <div class="flex items-center gap-3">
                        <x-filament::button type="submit" color="primary" wire:loading.attr="disabled" wire:target="confirmTwoFactorAuthentication">
                            <span wire:loading.remove wire:target="confirmTwoFactorAuthentication">Confirm</span>
                            <span wire:loading wire:target="confirmTwoFactorAuthentication">Confirming…</span>
                        </x-filament::button>
                        <x-filament::button type="button" color="gray" wire:click="disableTwoFactorAuthentication">
                            Cancel
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="space-y-6">
            <p class="text-sm font-medium text-green-700 dark:text-green-400">
                Two-factor authentication is enabled.
            </p>

            <div>
                <button type="button"
                    wire:click="$toggle('showingRecoveryCodes')"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 underline">
                    {{ $showingRecoveryCodes ? 'Hide' : 'Show' }} recovery codes
                </button>

                @if ($showingRecoveryCodes)
                    <div class="mt-3 grid grid-cols-2 gap-2 rounded bg-gray-100 p-4 font-mono text-sm dark:bg-gray-900">
                        @foreach ($user->recoveryCodes() as $code)
                            <code>{{ $code }}</code>
                        @endforeach
                    </div>
                @endif
            </div>

            <form wire:submit="regenerateRecoveryCodes" class="space-y-4">
                {{ $this->form }}
                <div class="flex flex-wrap items-center gap-3">
                    <x-filament::button type="submit" color="gray" wire:loading.attr="disabled" wire:target="regenerateRecoveryCodes">
                        Regenerate recovery codes
                    </x-filament::button>
                    <x-filament::button type="button" color="danger" wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled" wire:target="disableTwoFactorAuthentication">
                        Disable two-factor
                    </x-filament::button>
                </div>
            </form>
        </div>
    @endif
</x-section-card>
