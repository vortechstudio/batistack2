<?php

namespace App\Models\Commerce;

use App\Enums\Commerce\StatusFacture;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactureFournisseur extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function reglements()
    {
        return $this->hasMany(FactureFournisseurPaiement::class);
    }
    protected function casts(): array
    {
        return [
            'date_facture' => 'date',
            'date_echeance' => 'date',
            'status' => StatusFacture::class,
        ];
    }
}
