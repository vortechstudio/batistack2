<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use App\Models\RH\Employe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChantierRessources extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function getDurationAttribute()
    {
        return Carbon::createFromTimestamp(strtotime($this->date_affectation))->diffInHours(Carbon::createFromTimestamp(strtotime($this->date_fin)));
    }

    protected function casts(): array
    {
        return [
            'date_affectation' => 'date',
            'date_fin' => 'date',
        ];
    }
}
