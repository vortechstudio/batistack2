<?php

declare(strict_types=1);

namespace App\Models\RH\Paie;

use App\Models\RH\Employe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FichePaie extends Model
{
    protected $guarded = [];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function profilPaie(): BelongsTo
    {
        return $this->belongsTo(ProfilPaie::class);
    }

    protected function casts(): array
    {
        return [
            'periode' => 'date',
        ];
    }
}
