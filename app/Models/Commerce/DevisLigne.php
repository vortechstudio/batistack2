<?php

declare(strict_types=1);

namespace App\Models\Commerce;

use App\Enums\Commerce\TypeDevisLigne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DevisLigne extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }

    protected function casts(): array
    {
        return [
            'type' => TypeDevisLigne::class,
        ];
    }
}
