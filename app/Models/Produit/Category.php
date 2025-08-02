<?php

declare(strict_types=1);

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this::class);
    }
}
