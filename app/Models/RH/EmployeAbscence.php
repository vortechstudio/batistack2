<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Enums\RH\StatusAbsence;
use App\Enums\RH\TypeAbsence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EmployeAbscence extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function getReferenceAttribute()
    {
        $now = now();

        return 'HL'.$now->year.$now->month.'-'.$this->id;
    }

    public function getNbJourAttribute()
    {
        return $this->date_debut->diffInDays($this->date_fin);
    }

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'type' => TypeAbsence::class,
            'status' => StatusAbsence::class,
        ];
    }
}
