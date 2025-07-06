<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactureFournisseurLigne extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function factureFournisseur(): BelongsTo
    {
        return $this->belongsTo(FactureFournisseur::class);
    }
}
