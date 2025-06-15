<?php

namespace App\Models\Core;

use App\Enums\Core\BankMouvementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyBankAccountMouvement extends Model
{
    protected $guarded = [];
    public function account(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class);
    }

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'transaction_date' => 'date',
            'value_date' => 'date',
            'future' => 'boolean',
            'type' => BankMouvementType::class,
        ];
    }
}
