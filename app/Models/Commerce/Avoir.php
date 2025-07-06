<?php

namespace App\Models\Commerce;

use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avoir extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function lignes()
    {
        return $this->hasMany(AvoirLigne::class);
    }

    protected function casts(): array
    {
        return [
            'date_avoir' => 'date',
        ];
    }
}
