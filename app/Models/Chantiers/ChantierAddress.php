<?php

namespace App\Models\Chantiers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChantierAddress extends Model
{
    use HasFactory;
    protected $guarded = [];

    public $timestamps = false;

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }
}
