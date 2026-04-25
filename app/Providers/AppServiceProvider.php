<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Filament\Notifications\Livewire\DatabaseNotifications;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        DB::prohibitDestructiveCommands($this->app->isProduction());
        Date::use(CarbonImmutable::class);
        Vite::prefetch(concurrency: 3);

        DatabaseNotifications::trigger('filament.notifications.database-notifications-trigger');
    }
}
