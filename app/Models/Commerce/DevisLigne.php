<?php

namespace App\Models\Commerce;

use App\Enums\Commerce\TypeDevisLigne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevisLigne extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];


    protected function casts(): array
    {
        return [
            'type' => TypeDevisLigne::class,
        ];
    }

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }
}
