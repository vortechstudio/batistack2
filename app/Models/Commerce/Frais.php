<?php

declare(strict_types=1);

namespace App\Models\Commerce;

use App\Enums\Commerce\CategoryFee;
use App\Models\Chantiers\Chantiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class frais extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    protected function casts(): array
    {
        return [
            'date_frais' => 'date',
            'category' => CategoryFee::class,
        ];
    }
}
