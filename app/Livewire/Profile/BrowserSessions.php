<?php

namespace App\Livewire\Profile;

use App\Actions\Profile\LogoutOtherBrowserSessions;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BrowserSessions extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public bool $confirmingLogout = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'gap-6'])
            ->components([
                TextInput::make('password')
                    ->label('Current password')
                    ->password()
                    ->revealable(),
            ])
            ->statePath('data');
    }

    #[Computed]
    public function sessions(): Collection
    {
        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(fn ($row) => (object) [
                'id' => $row->id,
                'ip_address' => $row->ip_address,
                'is_current_device' => $row->id === session()->getId(),
                'last_active' => Carbon::createFromTimestamp($row->last_activity)->diffForHumans(),
                'agent' => $this->parseAgent($row->user_agent),
            ])
            ->values();
    }

    public function logoutOtherBrowserSessions(LogoutOtherBrowserSessions $action): void
    {
        $password = (string) ($this->data['password'] ?? '');

        try {
            $action->logout($password);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'data.password' => $e->errors()['password'] ?? [__('The provided password is invalid.')],
            ]);
        }

        $this->data['password'] = null;
        $this->confirmingLogout = false;
        unset($this->sessions);

        Notification::make()
            ->title('Other browser sessions logged out.')
            ->success()
            ->send();
    }

    private function parseAgent(?string $ua): array
    {
        if (! $ua) {
            return ['browser' => 'Unknown browser', 'platform' => 'Unknown platform'];
        }

        $browser = match (true) {
            str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/') => 'Opera',
            str_contains($ua, 'Chrome/') => 'Chrome',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Unknown browser',
        };

        $platform = match (true) {
            (bool) preg_match('/iPhone|iPad|iPod/', $ua) => 'iOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Mac OS X') => 'macOS',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Unknown platform',
        };

        return ['browser' => $browser, 'platform' => $platform];
    }

    public function render(): View
    {
        return view('livewire.profile.browser-sessions');
    }
}
