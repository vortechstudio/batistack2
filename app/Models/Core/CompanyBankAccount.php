<?php

declare(strict_types=1);

namespace App\Models\Core;

use App\Enums\Core\BankAccountType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CompanyBankAccount extends Model
{
    protected $guarded = [];

    public function companyBank(): BelongsTo
    {
        return $this->belongsTo(CompanyBank::class);
    }

    protected function casts(): array
    {
        return [
            'type' => BankAccountType::class,
        ];
    }
}
