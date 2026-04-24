<?php

namespace App\Livewire\Profile;

use App\Actions\Profile\DeleteUserAccount;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DeleteAccount extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public bool $confirmingDeletion = false;

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

    public function confirmDeletion(): void
    {
        $this->confirmingDeletion = true;
    }

    public function deleteAccount(DeleteUserAccount $action): void
    {
        $password = (string) ($this->data['password'] ?? '');

        try {
            $action->delete(Auth::user(), $password);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'data.password' => $e->errors()['password'] ?? [__('The provided password is invalid.')],
            ]);
        }

        $this->redirect('/', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.profile.delete-account');
    }
}
