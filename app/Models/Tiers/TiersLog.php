<?php

declare(strict_types=1);

namespace App\Models\Tiers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TiersLog extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    protected function casts(): array
    {
        return [
            'event_day' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }
}
