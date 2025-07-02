<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use App\Enums\Chantiers\StatusChantier;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(ChantierTask::class);
    }

    public function logs()
    {
        return $this->hasMany(ChantierLog::class);
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

    public function getAvancements(): array
    {
        $total_task = $this->tasks->count();
        $finish_task = $this->tasks()->where('status', '!=', 'todo')->count();
        $percent = $finish_task != 0 ? $total_task / $finish_task * 100 : 0;

        return [
            "percent" => $percent,
            "color" => $percent <= 33 ? "red" : ($percent > 34 && $percent <= 66 ? "amber" : "green"),
        ];
    }
}
