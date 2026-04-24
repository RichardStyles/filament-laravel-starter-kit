<?php

namespace App\Livewire\Profile;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;

class UpdateProfileInformation extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'avatar_path' => $user->avatar_path,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->extraAttributes(['class' => 'gap-6'])
            ->components([
                FileUpload::make('avatar_path')
                    ->label('Avatar')
                    ->avatar()
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater): void
    {
        $data = $this->form->getState();

        $avatarPath = $data['avatar_path'] ?? null;
        unset($data['avatar_path']);

        try {
            $updater->update(Auth::user()->fresh(), $data);
        } catch (ValidationException $e) {
            throw $this->remapErrors($e);
        }

        Auth::user()->forceFill(['avatar_path' => $avatarPath])->save();
        Auth::user()->refresh();

        Notification::make()
            ->title('Profile updated.')
            ->success()
            ->send();
    }

    public function resendEmailVerification(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        Notification::make()
            ->title('Verification link sent.')
            ->success()
            ->send();
    }

    private function remapErrors(ValidationException $e): ValidationException
    {
        return ValidationException::withMessages(
            collect($e->errors())
                ->mapWithKeys(fn ($messages, $field): array => ["data.$field" => $messages])
                ->all()
        );
    }

    public function render(): View
    {
        return view('livewire.profile.update-profile-information');
    }
}
