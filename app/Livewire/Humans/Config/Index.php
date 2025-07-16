<?php

namespace App\Livewire\Humans\Config;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(settings()->all()->toArray());
    }

    

    #[Title('Configuration')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.config.index');
    }
}
