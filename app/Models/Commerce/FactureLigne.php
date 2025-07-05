<?php

declare(strict_types=1);

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FactureLigne extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }
}
