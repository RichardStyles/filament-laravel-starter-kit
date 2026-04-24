<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.authenticated')]
#[Title('Your profile')]
class Profile extends Component
{
    public function render(): View
    {
        return view('livewire.profile.profile');
    }
}
