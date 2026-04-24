@php
    $user = auth()->user();
    $avatarUrl = 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($user->email ?? ''))).'?d=mp&s=256';

    $links = [
        ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
    ];
@endphp

<nav
    x-data="{ mobileOpen: false, userOpen: false }"
    class="border-b border-gray-200 bg-white dark:border-white/10 dark:bg-gray-800/50"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="{{ config('app.name') }}" class="h-8 w-auto dark:hidden" />
                        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="{{ config('app.name') }}" class="h-8 w-auto not-dark:hidden" />
                    </a>
                </div>
                <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                    @foreach ($links as $link)
                        <a
                            href="{{ $link['href'] }}"
                            @if ($link['active']) aria-current="page" @endif
                            @class([
                                'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium',
                                'border-indigo-600 text-gray-900 dark:border-indigo-500 dark:text-white' => $link['active'],
                                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-white/20 dark:hover:text-gray-200' => ! $link['active'],
                            ])
                        >{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @livewire('database-notifications')

                <div
                    class="relative ml-3"
                    @click.outside="userOpen = false"
                    @keydown.escape.window="userOpen = false"
                >
                    <button
                        type="button"
                        @click="userOpen = !userOpen"
                        :aria-expanded="userOpen.toString()"
                        aria-haspopup="true"
                        class="relative flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-indigo-500"
                    >
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">Open user menu</span>
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="size-8 rounded-full outline -outline-offset-1 outline-black/5 dark:outline-white/10" />
                    </button>

                    <div
                        x-show="userOpen"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        role="menu"
                        aria-orientation="vertical"
                        class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg outline outline-black/5 dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
                    >
                        <a href="{{ route('profile') }}" role="menuitem" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700">Your profile</a>
                        <a href="{{ route('settings') }}" role="menuitem" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700">Settings</a>
                        <button type="button" wire:click="signOut" role="menuitem" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700">Sign out</button>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
                <button
                    type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-menu"
                    class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white dark:focus:outline-indigo-500"
                >
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!mobileOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" x-show="mobileOpen" x-cloak class="block sm:hidden">
        <div class="space-y-1 pt-2 pb-3">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @if ($link['active']) aria-current="page" @endif
                    @class([
                        'block border-l-4 py-2 pr-4 pl-3 text-base font-medium',
                        'border-indigo-600 bg-indigo-50 text-indigo-700 dark:border-indigo-500 dark:bg-indigo-600/10 dark:text-indigo-300' => $link['active'],
                        'border-transparent text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:hover:border-white/20 dark:hover:bg-white/5 dark:hover:text-gray-200' => ! $link['active'],
                    ])
                >{{ $link['label'] }}</a>
            @endforeach
        </div>
        <div class="border-t border-gray-200 pt-4 pb-3 dark:border-gray-700">
            <div class="flex items-center px-4">
                <div class="shrink-0">
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="size-10 rounded-full outline -outline-offset-1 outline-black/5 dark:outline-white/10" />
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800 dark:text-white">{{ $user->name }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                </div>
                <button
                    type="button"
                    @click="$dispatch('open-modal', { id: 'database-notifications' })"
                    class="relative ml-auto shrink-0 rounded-full p-1 text-gray-400 hover:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:text-gray-400 dark:hover:text-white dark:focus:outline-indigo-500"
                >
                    <span class="absolute -inset-1.5"></span>
                    <span class="sr-only">View notifications</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if ($unreadNotificationsCount > 0)
                        <span class="absolute top-0 right-0 inline-flex min-w-4 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-semibold text-white ring-2 ring-white dark:bg-indigo-500 dark:ring-gray-800">
                            {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                        </span>
                    @endif
                </button>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200">Your profile</a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200">Settings</a>
                <button type="button" wire:click="signOut" class="block w-full px-4 py-2 text-left text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200">Sign out</button>
            </div>
        </div>
    </div>
</nav>
