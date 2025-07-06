<?php

namespace App\Models\RH\Paie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FichePaieLigne extends Model
{
    public $timestamps = false;

    public function fichePaie(): BelongsTo
    {
        return $this->belongsTo(FichePaie::class);
    }

    public function rubriquePaie(): BelongsTo
    {
        return $this->belongsTo(RubriquePaie::class);
    }
}
