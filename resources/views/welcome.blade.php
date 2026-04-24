<x-layouts.app :title="config('app.name')">
    <div class="relative min-h-screen overflow-hidden bg-gray-100 dark:bg-gray-900">
        <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-indigo-300 to-purple-400 opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72rem] dark:opacity-20"></div>
        </div>

        <header class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="{{ config('app.name') }}" class="h-8 w-auto dark:hidden" />
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="{{ config('app.name') }}" class="h-8 w-auto not-dark:hidden" />
                <span class="text-base font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
            </a>

            <nav class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3.5 py-2 font-medium text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden font-medium text-gray-700 hover:text-gray-900 sm:inline dark:text-gray-300 dark:hover:text-white">Sign in</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3.5 py-2 font-medium text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">Get started</a>
                @endauth
            </nav>
        </header>

        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="py-20 text-center sm:py-28">
                <p class="mb-4 text-sm font-semibold tracking-wide text-indigo-600 uppercase dark:text-indigo-400">Laravel starter kit</p>
                <h1 class="mx-auto max-w-3xl text-balance text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-white">
                    Ship your next Laravel app in an afternoon.
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-balance text-lg text-gray-600 dark:text-gray-300">
                    A batteries-included starter with authentication, two-factor, role-based authorization, profile management, and an admin panel — all wired together with Livewire 4, Filament 5, and Tailwind 4.
                </p>
                <div class="mt-10 flex items-center justify-center gap-4">
                    @guest
                        <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">Create your account</a>
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-900 dark:text-white">Sign in <span aria-hidden="true">→</span></a>
                    @else
                        <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">Go to dashboard</a>
                    @endguest
                </div>
            </section>

            <section class="grid gap-6 pb-20 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Authentication out of the box', 'body' => 'Login, registration, password reset, email verification, and two-factor TOTP — all wired through Fortify with Livewire forms.'],
                    ['title' => 'Roles and policies', 'body' => 'Spatie permissions seeded with admin and user roles. A worked UserPolicy demonstrates the pattern.'],
                    ['title' => 'Quality tooling', 'body' => 'Pint, Larastan, Rector, Pest 4, and a GitHub Actions matrix all configured. composer check runs the full pipeline.'],
                ] as $feature)
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800/50 dark:ring-white/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ $feature['body'] }}</p>
                    </div>
                @endforeach
            </section>
        </main>

        <footer class="border-t border-gray-200 dark:border-white/10">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                <p class="text-xs text-gray-500 dark:text-gray-400">&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
                <a href="https://laravel.com" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Built with Laravel</a>
            </div>
        </footer>
    </div>
</x-layouts.app>
