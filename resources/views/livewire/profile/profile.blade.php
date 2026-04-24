<div class="mx-auto max-w-5xl">
    <div class="mb-8 space-y-2">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Your profile</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Manage your account details, security, and sessions.</p>
    </div>

    <div class="space-y-6">
        @livewire(\App\Livewire\Profile\UpdateProfileInformation::class)
        @livewire(\App\Livewire\Profile\UpdatePassword::class)
        @livewire(\App\Livewire\Profile\TwoFactorAuthentication::class)
        @livewire(\App\Livewire\Profile\BrowserSessions::class)
        @livewire(\App\Livewire\Profile\DeleteAccount::class)
    </div>

    <x-filament-actions::modals />
</div>
