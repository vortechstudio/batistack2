<?php

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this::class);
    }
}
