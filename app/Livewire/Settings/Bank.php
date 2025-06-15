<?php

namespace App\Livewire\Settings;

use App\Jobs\Core\Bank\ImportBankMouvement;
use App\Models\Core\CompanyBank;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

class Bank extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public Collection $companyBanks;

    public function mount(): void
    {
        $this->companyBanks = CompanyBank::all()->load('bank', 'company', 'accounts');
    }
    public function createBankAction(): Action
    {
        return CreateAction::make('createBankAction')
            ->label('Ajouter une banque')
            ->url(route('api.bank.connectAccount'));
    }

    public function refreshMouvement(int $bank_id): void
    {
        $bank = CompanyBank::find($bank_id);
        ImportBankMouvement::dispatch($bank);
    }

    #[Title('Banques')]
    public function render()
    {
        return view('livewire.settings.bank')
            ->layout('components.layouts.settings');
    }
}
