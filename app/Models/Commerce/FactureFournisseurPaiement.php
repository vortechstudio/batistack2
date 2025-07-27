<?php

declare(strict_types=1);

namespace App\Models\Commerce;

use App\Models\Core\ModeReglement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FactureFournisseurPaiement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function factureFournisseur(): BelongsTo
    {
        return $this->belongsTo(FactureFournisseur::class);
    }

    public function modeReglement(): BelongsTo
    {
        return $this->belongsTo(ModeReglement::class);
    }

    protected function casts(): array
    {
        return [
            'date_paiement' => 'date',
        ];
    }
}
