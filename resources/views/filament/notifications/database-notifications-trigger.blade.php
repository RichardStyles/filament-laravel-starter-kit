<button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:text-gray-400 dark:hover:text-white dark:focus:outline-indigo-500">
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
