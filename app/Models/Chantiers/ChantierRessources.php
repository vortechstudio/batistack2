<?php

namespace App\Models\Chantiers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChantierRessources extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    protected function casts(): array
    {
        return [
            'date_affectation' => 'date',
            'date_fin' => 'date',
        ];
    }
}
