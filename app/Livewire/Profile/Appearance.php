<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use Filament\Forms\Components\Radio;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Appearance extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public const array PREFERENCES = ['system', 'light', 'dark'];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'appearance_preference' => Auth::user()->appearance_preference ?? 'system',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Radio::make('appearance_preference')
                    ->label('Theme')
                    ->options([
                        'system' => 'Match system',
                        'light' => 'Light',
                        'dark' => 'Dark',
                    ])
                    ->descriptions([
                        'system' => 'Follows your operating-system setting.',
                        'light' => 'Always use the light theme.',
                        'dark' => 'Always use the dark theme.',
                    ])
                    ->required()
                    ->in(self::PREFERENCES),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Auth::user()->forceFill(['appearance_preference' => $data['appearance_preference']])->save();

        $this->dispatch('appearance-updated', preference: $data['appearance_preference']);

        Notification::make()
            ->title('Appearance updated.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.profile.appearance');
    }
}
