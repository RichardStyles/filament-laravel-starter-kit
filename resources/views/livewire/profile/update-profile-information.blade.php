<x-profile.card
    title="Personal information"
    description="Update your name and email address.">

    @if (! auth()->user()->hasVerifiedEmail())
        <div class="mb-6 rounded-lg bg-yellow-50 p-4 ring-1 ring-yellow-200 dark:bg-yellow-950/40 dark:ring-yellow-800/60">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                Your email address is unverified.
                <button type="button" wire:click="resendEmailVerification" class="font-medium underline hover:text-yellow-900 dark:hover:text-yellow-100">
                    Resend the verification email
                </button>.
            </p>
        </div>
    @endif

    <form wire:submit="updateProfileInformation" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button
                type="submit"
                color="primary"
                wire:loading.attr="disabled"
                wire:target="updateProfileInformation">
                <span wire:loading.remove wire:target="updateProfileInformation">Save</span>
                <span wire:loading wire:target="updateProfileInformation">Saving…</span>
            </x-filament::button>
        </div>
    </form>
</x-profile.card>
