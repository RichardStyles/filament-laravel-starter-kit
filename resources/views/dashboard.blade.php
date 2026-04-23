<x-layouts.app :title="'Dashboard'">
    <main class="min-h-screen px-6 py-12">
        <div class="mx-auto max-w-3xl">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-neutral-600 hover:text-neutral-900">
                        Log out
                    </button>
                </form>
            </div>

            <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-neutral-200">
                <p class="text-neutral-700">
                    Welcome back, {{ auth()->user()->name }}.
                </p>
            </div>
        </div>
    </main>
</x-layouts.app>
