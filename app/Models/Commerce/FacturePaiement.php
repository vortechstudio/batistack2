<?php

namespace App\Models\Commerce;

use App\Models\Core\ModeReglement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacturePaiement extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function modeReglement(): BelongsTo
    {
        return $this->belongsTo(ModeReglement::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    protected function casts(): array
    {
        return [
            'date_paiement' => 'date',
        ];
    }
}
