<?php

namespace App\Livewire\Profile;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Livewire\Component;

class UpdatePassword extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->extraAttributes(['class' => 'gap-6'])
            ->components([
                TextInput::make('current_password')
                    ->label('Current password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('password')
                    ->label('New password')
                    ->password()
                    ->revealable()
                    ->required(),
                TextInput::make('password_confirmation')
                    ->label('Confirm new password')
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function updatePassword(UpdatesUserPasswords $updater): void
    {
        $data = $this->form->getState();

        try {
            $updater->update(Auth::user(), $data);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages(
                collect($e->errors())
                    ->mapWithKeys(fn ($messages, $field): array => ["data.$field" => $messages])
                    ->all()
            );
        }

        $this->form->fill();

        Notification::make()
            ->title('Password updated.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.profile.update-password');
    }
}
