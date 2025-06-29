<?php

namespace App\Livewire\Settings;

use App\Jobs\Core\Bank\ImportBankMouvement;
use App\Models\Core\CompanyBank;
use App\Models\Core\CompanyBankAccount;
use App\Services\Bridge;
use Illuminate\Http\Request;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreateBank extends Component
{
    public ?array $item;

    public ?array $accounts;

    public CompanyBank $banque;

    public $code;

    public $lettrage;

    public function mount(Request $request): void
    {
        $bridge = new Bridge;
        $bridge->getAccessToken();
        $this->item = $bridge->get('aggregation/items/'.$request->get('item_id'), null, cache('bridge_access_token'));
        $this->accounts = $bridge->get('aggregation/accounts', ['item_id' => $request->get('item_id')], cache('bridge_access_token'));
        $banks = \App\Models\Core\Bank::where('bridge_id', $this->item['provider_id'])->first();

        $this->accounts = collect($this->accounts['resources'])->filter(fn ($item) => isset($item['balance']))->toArray();

        if (CompanyBank::where('item_id', $request->item_id)->exists()) {
            CompanyBank::where('item_id', $request->item_id)
                ->first()
                ->update([
                    'item_id' => $request->item_id,
                    'bank_id' => $banks->id,
                    'company_id' => \App\Models\Core\Company::first()->id,
                    'last_refreshed_at' => now(),
                ]);
        } else {
            CompanyBank::create([
                'item_id' => $request->get('item_id'),
                'bank_id' => $banks->id,
                'company_id' => \App\Models\Core\Company::first()->id,
                'last_refreshed_at' => now(),
            ]);
        }

        $this->banque = CompanyBank::where('item_id', $request->get('item_id'))->first();

        foreach ($this->accounts as $account) {
            CompanyBankAccount::updateOrCreate([
                'account_id' => $account['id'],
            ], [
                'account_id' => $account['id'],
                'name' => $account['name'],
                'balance' => $account['balance'],
                'instante' => $account['instant_balance'] ?? '0.00',
                'type' => $account['type'],
                'iban' => $account['iban'] ?? null,
                'company_bank_id' => $this->banque['id'],
            ]);
        }

        ImportBankMouvement::dispatch($this->banque);
        toastr()->addSuccess('Les Informations bancaires ont bien été ajouté !');
        toastr()->addInfo("L'aggrégation de vos comptes ont commencée, vous serez alerté de sa disposition");
        $this->redirect(route('settings.bank'));
    }

    #[Title("Ajout d'une banque & comptes bancaires")]
    public function render()
    {
        return view('livewire.settings.create-bank')
            ->layout('components.layouts.settings');
    }
}
