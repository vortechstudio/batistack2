<?php

namespace App\Livewire\Portail\Salarie;

use App\Models\Core\Bank as CoreBank;
use App\Models\RH\EmployeBank;
use App\Services\Bridge;
use App\Services\OpenIban;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Bank extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        $hasAccounts = EmployeBank::where('employe_id', Auth::user()->employe->id)->exists();

        return $table
            ->query(EmployeBank::query()->where('employe_id', Auth::user()->employe->id))
            ->emptyStateDescription("Aucun compte bancaire lié à votre compte.")
            ->emptyStateHeading("Aucun compte bancaire")
            ->headerActions($hasAccounts ? [] : [
                Action::make('connect')
                    ->label("Lier un compte bancaire")
                    ->action(fn () => $this->connectAccount()),
            ])
            ->columns([
                TextColumn::make('bank_id')
                    ->label("Banque"),

                TextColumn::make('iban')
                    ->label("Information Bancaire"),
            ]);
    }

    public function connectAccount()
    {
        $authToken = app(Bridge::class)->post('aggregation/authorization/token', [
            'user_uuid' => Auth::user()->employe->bridge_user_id,
        ]);

        cache()->put('bridge_access_token', $authToken['access_token']);

        $bridge = app(Bridge::class)->post('/aggregation/connect-sessions', [
            'user_email' => Auth::user()->email,
            'country_code' => 'FR',
            'callback_url' => config('app.url').'/portail/salarie/bank?state=banker',
        ], $authToken['access_token']);

        return $this->redirect($bridge['url']);
    }

    #[Title("Mon compte bancaire")]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        if(request()->get('state') == 'banker') {

            $accounts = app(Bridge::class)->get('/aggregation/accounts?item_id='.request()->get('item_id'), [], cache()->get('bridge_access_token'));

            $collect = collect($accounts['resources'])
                ->filter(function ($account) {
                    return isset($account['data_access']) &&
                           $account['data_access'] === 'enabled' &&
                           isset($account['item_id']) &&
                           $account['item_id'] == '11372128';
                });

            foreach($collect as $account) {
                $bank = CoreBank::where('bridge_id', $account['provider_id'])->first();
                Auth::user()->employe->bank()->create([
                    "employe_id" => Auth::user()->employe->id,
                    "bank_id" => $bank->id,
                    "iban" => $account['iban'],
                    "bridge_id" => $account['id'],
                    "bic" => app(OpenIban::class)->info($account['iban'])['bic'] ?? null,
                ]);
            }
        }
        return view('livewire.portail.salarie.bank');
    }
}
