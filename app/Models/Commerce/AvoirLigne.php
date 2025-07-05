<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvoirLigne extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function avoir(): BelongsTo
    {
        return $this->belongsTo(Avoir::class);
    }
}
