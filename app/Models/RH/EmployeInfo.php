<?php

namespace App\Models\RH;

use App\Enums\RH\ProcessEmploye;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeInfo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'process' => ProcessEmploye::class,
            'cni_verified_at' => 'datetime',
            'vital_verified_at' => 'datetime',
            'btp_card_verified_at' => 'datetime',
        ];
    }
}
