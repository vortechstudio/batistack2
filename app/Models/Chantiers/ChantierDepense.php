<?php

namespace App\Models\Chantiers;

use App\Enums\Chantiers\TypeDepenseChantier;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChantierDepense extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    protected function casts(): array
    {
        return [
            'date_depense' => 'date',
            'type_depense' => TypeDepenseChantier::class,
        ];
    }
}
