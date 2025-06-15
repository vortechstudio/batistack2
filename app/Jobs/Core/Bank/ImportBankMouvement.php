<?php

namespace App\Jobs\Core\Bank;

use App\Events\Core\Bank\AggregateMouvementEvent;
use App\Models\Core\Company;
use App\Models\Core\CompanyBank;
use App\Models\Core\CompanyBankAccountMouvement;
use App\Models\User;
use App\Notifications\Core\Bank\UpdateBankAccount;
use App\Services\Bridge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportBankMouvement implements ShouldQueue
{
    use Queueable;
    private string $token;
    public Bridge $api;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CompanyBank $bank,
    )
    {
        $this->api = new Bridge();
        $this->getAccessToken();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info("Info Bank", ["Bank" => $this->bank, "Accounts" => $this->bank->accounts]);
        foreach ($this->bank->accounts as $account) {
            $transactions = $this->api->get('aggregation/transactions?limit=500&account_id='.$account->account_id.'&min_date=2025-01-01', null, $this->token);

            foreach ($transactions['resources'] as $transaction) {
                CompanyBankAccountMouvement::updateOrCreate([
                    'transaction_id' =>  $transaction['id'],
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

    private function getAccessToken(): void
    {
        $this->token = $this->api->post('aggregation/authorization/token', [
            'user_uuid' => Company::first()->bridge_client_id,
        ])['access_token'];
    }
}
