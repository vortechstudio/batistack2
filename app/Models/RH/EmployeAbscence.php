<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        ];
    }
}
