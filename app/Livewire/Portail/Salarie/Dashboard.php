<?php

declare(strict_types=1);

namespace App\Livewire\Portail\Salarie;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Dashboard extends Component
{
    public User $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    #[Title('Tableau de Bord')]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        return view('livewire.portail.salarie.dashboard');
    }
}
