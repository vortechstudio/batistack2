<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use Illuminate\Database\Eloquent\Model;

final class ChantierPhotos extends Model
{
    protected $guarded = [];

    public function chantier()
    {
        return $this->belongsTo(Chantiers::class);
    }
}
