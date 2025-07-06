<?php

namespace App\Models\RH;

use App\Models\Chantiers\Chantiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployePointage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
