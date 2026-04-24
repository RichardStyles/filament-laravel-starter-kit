<x-layouts.authenticated :title="'Dashboard'">
    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('kit.dashboard.heading') }}</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('kit.dashboard.welcome_back', ['name' => auth()->user()->name]) }}
        </p>
    </x-slot:header>

    @php
        $user = auth()->user();
        $accountAge = $user->created_at->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, parts: 1);
        $sessionCount = \Illuminate\Support\Facades\DB::table(config('session.table', 'sessions'))
            ->where('user_id', $user->getAuthIdentifier())
            ->count();
        $unreadNotifications = $user->unreadNotifications()->count();

        $stats = [
            ['label' => __('kit.dashboard.stats.account_age'), 'value' => $accountAge, 'meta' => 'since '.$user->created_at->toFormattedDateString()],
            ['label' => __('kit.dashboard.stats.active_sessions'), 'value' => (string) $sessionCount, 'meta' => $sessionCount === 1 ? 'this device' : 'across your devices'],
            ['label' => __('kit.dashboard.stats.unread_notifications'), 'value' => (string) $unreadNotifications, 'meta' => 'in the bell'],
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800/50 dark:ring-white/10">
                <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $stat['label'] }}</dt>
                <dd class="mt-2 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $stat['value'] }}</dd>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $stat['meta'] }}</p>
            </div>
        @endforeach
    </div>

    <x-section-card
        title="Get started"
        description="A quick lap through the kit so you know what's wired and what to customize next."
        class="mt-6"
    >
        <ul class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
            <li class="flex items-start gap-3">
                <span class="mt-0.5 inline-flex size-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">1</span>
                <span>
                    Update your <a href="{{ route('settings') }}" class="font-medium text-indigo-600 underline hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">profile and security</a> — name, email, avatar, password, and two-factor authentication.
                </span>
            </li>
            <li class="flex items-start gap-3">
                <span class="mt-0.5 inline-flex size-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">2</span>
                <span>
                    Pick an <a href="{{ route('settings') }}" class="font-medium text-indigo-600 underline hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">appearance preference</a> — system, light, or dark.
                </span>
            </li>
            <li class="flex items-start gap-3">
                <span class="mt-0.5 inline-flex size-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">3</span>
                <span>
                    Swap the placeholder logo and copy in <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">resources/views/livewire/navigation.blade.php</code> and <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">resources/views/welcome.blade.php</code>.
                </span>
            </li>
        </ul>
    </x-section-card>
</x-layouts.authenticated>
