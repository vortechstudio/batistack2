<?php

declare(strict_types=1);

namespace App\Models\Tiers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TiersContact extends Model
{
    /** @use HasFactory<\Database\Factories\Tiers\TiersContactFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }
}
