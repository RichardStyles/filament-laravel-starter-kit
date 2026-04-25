<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApiTokens extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public ?string $newPlainTextToken = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('token_name')
                    ->label('Token name')
                    ->placeholder('e.g. CLI deploy script')
                    ->required()
                    ->maxLength(120),
            ])
            ->statePath('data');
    }

    public function createToken(): void
    {
        $data = $this->form->getState();

        $user = Auth::user() ?? throw new AuthenticationException;
        $this->newPlainTextToken = $user->createToken($data['token_name'])->plainTextToken;

        $this->form->fill();

        Notification::make()
            ->title('Token created — copy it now, it will not be shown again.')
            ->success()
            ->send();
    }

    public function revoke(int $tokenId): void
    {
        $user = Auth::user() ?? throw new AuthenticationException;
        $user->tokens()->whereKey($tokenId)->delete();

        Notification::make()
            ->title('Token revoked.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        $user = Auth::user() ?? throw new AuthenticationException;

        return view('livewire.profile.api-tokens', [
            'tokens' => $user->tokens()->latest()->get(),
        ]);
    }
}
