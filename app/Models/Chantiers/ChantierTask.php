<?php

namespace App\Models\Chantiers;

use App\Enums\Chantiers\PriorityChantierTask;
use App\Enums\Chantiers\StatusChantierTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChantierTask extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function assigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    protected function casts(): array
    {
        return [
            'date_debut_prevu' => 'date',
            'date_fin_prevue' => 'date',
            'date_debut_reel' => 'date',
            'date_fin_reel' => 'date',
            'status' => StatusChantierTask::class,
            'priority' => PriorityChantierTask::class,
        ];
    }
}
