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

    /** @var array{password?: string|null}|null */
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

    /**
     * @return Collection<int, object{id: string, ip_address: string|null, is_current_device: bool, last_active: string, agent: array{browser: string, platform: string}}&\stdClass>
     */
    #[Computed]
    public function sessions(): Collection
    {
        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function (object $row): object {
                $id = is_string($row->id) ? $row->id : '';
                $ipAddress = is_string($row->ip_address) ? $row->ip_address : null;
                $userAgent = is_string($row->user_agent) ? $row->user_agent : null;
                $lastActivity = is_int($row->last_activity) ? $row->last_activity : 0;

                return (object) [
                    'id' => $id,
                    'ip_address' => $ipAddress,
                    'is_current_device' => $id === session()->getId(),
                    'last_active' => Carbon::createFromTimestamp($lastActivity)->diffForHumans(),
                    'agent' => $this->parseAgent($userAgent),
                ];
            })
            ->values();
    }

    public function logoutOtherBrowserSessions(LogoutOtherBrowserSessions $action): void
    {
        $password = $this->data['password'] ?? '';

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

    /**
     * @return array{browser: string, platform: string}
     */
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
