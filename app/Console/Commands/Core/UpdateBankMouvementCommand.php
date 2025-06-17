<?php

namespace App\Console\Commands\Core;

use App\Models\Core\Company;
use App\Models\Core\CompanyBank;
use App\Models\Core\CompanyBankAccount;
use App\Models\Core\CompanyBankAccountMouvement;
use App\Models\User;
use App\Notifications\Core\Bank\UpdateBankAccount;
use App\Services\Bridge;
use Illuminate\Console\Command;

class UpdateBankMouvementCommand extends Command
{
    protected $signature = 'update:bank-mouvement';

    protected $description = 'Command description';
    public Bridge $bridge;

    public function handle(): void
    {
        $this->bridge = new Bridge();
        $accounts = CompanyBankAccount::all();

        foreach ($accounts as $account) {
            $infoCompte = $this->bridge->get('aggregation/accounts/'.$account->account_id, null, $this->getAccessToken());

            CompanyBankAccount::find($account->id)->update([
                'balance' =>  $infoCompte['balance'],
                'instante' =>   $infoCompte['instant_balance'] ?? 0,
                'updated_at' => now()
            ]);
            CompanyBank::find($account->company_bank_id)->update([
                'last_refreshed_at' => now()
            ]);

            $transactions = $this->bridge->get('aggregation/transactions?limit=500&account_id='.$account->account_id.'&min_date=2025-01-01', null, $this->getAccessToken());

            foreach ($transactions['resources'] as $transaction) {
                CompanyBankAccountMouvement::updateOrCreate([
                    'transaction_id' => $transaction['id'],
                ], [
                    'title' => $transaction['clean_description'],
                    'description' => $transaction['provider_description'],
                    'amount' => $transaction['amount'],
                    'date' => $transaction['date'] ?? null,
                    'booking_date' => $transaction['booking_date'] ?? null,
                    'transaction_date' => $transaction['transaction_date'] ?? null,
                    'value_date' => $transaction['value_date'] ?? null,
                    'category_id' => $transaction['category_id'] ?? null,
                    'type' => $transaction['operation_type'] ?? null,
                    'future' => $transaction['future'],
                    'company_bank_account_id' => $account->id,
                ]);
            }

            foreach (User::where('role', 'admin')->get() as $user) {
                $user->notify((new UpdateBankAccount($account))->delay(now()->addSeconds(15)));
            }
        }
    }

    private function getAccessToken(): mixed
    {
        return $this->bridge->post('aggregation/authorization/token', [
            'user_uuid' => Company::first()->bridge_client_id,
        ])['access_token'];
    }
}
