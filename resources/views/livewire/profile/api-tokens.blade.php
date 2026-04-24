<x-section-card
    title="API tokens"
    description="Issue tokens that authorize external clients to call /api/v1/* on your behalf. Tokens are shown once and stored as a one-way hash after that."
>
    @if ($newPlainTextToken)
        <div class="mb-6 rounded-lg bg-indigo-50 p-4 ring-1 ring-indigo-200 dark:bg-indigo-950/40 dark:ring-indigo-800/60">
            <p class="mb-2 text-sm font-medium text-indigo-900 dark:text-indigo-200">Copy this token now — you will not be able to view it again.</p>
            <code class="block break-all rounded bg-white px-3 py-2 text-xs text-gray-900 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-100 dark:ring-gray-700">{{ $newPlainTextToken }}</code>
        </div>
    @endif

    <form wire:submit="createToken" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button
                type="submit"
                color="primary"
                wire:loading.attr="disabled"
                wire:target="createToken">
                <span wire:loading.remove wire:target="createToken">Issue token</span>
                <span wire:loading wire:target="createToken">Issuing…</span>
            </x-filament::button>
        </div>
    </form>

    @if ($tokens->isNotEmpty())
        <div class="mt-8">
            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Existing tokens</h3>
            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($tokens as $token)
                    <li class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $token->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Created {{ $token->created_at->diffForHumans() }} ·
                                {{ $token->last_used_at ? 'Last used '.$token->last_used_at->diffForHumans() : 'Never used' }}
                            </p>
                        </div>
                        <button type="button" wire:click="revoke({{ $token->id }})" class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300">
                            Revoke
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-section-card>
