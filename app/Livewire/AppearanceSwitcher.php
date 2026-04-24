<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Profile\Appearance;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AppearanceSwitcher extends Component
{
    public string $preference = 'system';

    public function mount(): void
    {
        $this->preference = Auth::user()->appearance_preference ?? 'system';
    }

    public function setPreference(string $preference): void
    {
        Validator::validate(
            ['preference' => $preference],
            ['preference' => ['required', 'in:'.implode(',', Appearance::PREFERENCES)]],
        );

        $this->preference = $preference;

        Auth::user()->forceFill(['appearance_preference' => $preference])->save();

        $this->dispatch('appearance-updated', preference: $preference);
    }

    public function render(): View
    {
        return view('livewire.appearance-switcher');
    }
}
