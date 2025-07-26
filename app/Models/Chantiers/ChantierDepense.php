<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use App\Enums\Chantiers\TypeDepenseChantier;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

final class ChantierDepense extends Model
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

    public function getHasJustifyAttribute()
    {
        return Storage::disk('public')->exists('chantiers/'.$this->chantiers_id.'/justificatifs/depense_'.$this->tiers_id.'_'.$this->date_depense->format('d_m_Y').'_'.$this->type_depense->name.'.png');
    }

    public function getJustifyAttribute()
    {
        return 'depense_'.$this->tiers_id.'_'.$this->date_depense->format('d_m_Y').'_'.$this->type_depense->value.'.*';
    }

    protected function casts(): array
    {
        return [
            'date_depense' => 'date',
            'type_depense' => TypeDepenseChantier::class,
        ];
    }
}
