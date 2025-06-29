<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyBank extends Model
{
    protected $guarded = [];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(CompanyBankAccount::class, 'company_bank_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'last_refreshed_at' => 'timestamp',
        ];
    }
}
