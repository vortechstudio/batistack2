<?php

namespace App\Livewire\Core\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Notification extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public $notifications;

    public function mount(): void
    {
        $this->notifications = auth()->user()->notifications;
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->action(fn () => auth()->user()->notifications()->delete());
    }

    public function render()
    {
        return view('livewire.core.pages.notification')
            ->layout('components.layouts.app');
    }
}
