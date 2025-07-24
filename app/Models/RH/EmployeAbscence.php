<?php

namespace App\Models\RH;

use App\Models\RH\Employe;
use App\Enums\RH\TypeAbsence;
use App\Enums\RH\StatusAbsence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeAbscence extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
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

    public function getReferenceAttribute()
    {
        $now = now();
        return "HL".$now->year.$now->month."-".$this->id;
    }

    public function getNbJourAttribute()
    {
        return $this->date_debut->diffInDays($this->date_fin);
    }
}
