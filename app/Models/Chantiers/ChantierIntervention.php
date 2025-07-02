<?php

namespace App\Models\Chantiers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChantierIntervention extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    public function intervenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'intervenant_id');
    }

    protected function casts(): array
    {
        return [
            'date_intervention' => 'date',
            'facturable' => 'boolean',
        ];
    }
}
