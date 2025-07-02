<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use App\Enums\Chantiers\StatusChantier;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Chantiers extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function addresses()
    {
        return $this->hasMany(ChantierAddress::class);
    }

    public function interventions()
    {
        return $this->hasMany(ChantierIntervention::class);
    }

    public function tasks()
    {
        return $this->hasMany(ChantierTask::class);
    }

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin_prevu' => 'date',
            'date_fin_reel' => 'date',
            'status' => StatusChantier::class,
        ];
    }
}
