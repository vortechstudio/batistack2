<?php

declare(strict_types=1);

namespace App\Models\Commerce;

use App\Enums\Commerce\StatusDevis;
use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Devis extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function lignes()
    {
        return $this->hasMany(DevisLigne::class);
    }

    protected function casts(): array
    {
        return [
            'date_devis' => 'date',
            'date_validate' => 'date',
            'status' => StatusDevis::class,
        ];
    }
}
