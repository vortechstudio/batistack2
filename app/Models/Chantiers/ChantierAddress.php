<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChantierAddress extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }
}
