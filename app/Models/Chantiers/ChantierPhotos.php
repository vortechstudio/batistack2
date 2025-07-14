<?php

namespace App\Models\Chantiers;

use Illuminate\Database\Eloquent\Model;

class ChantierPhotos extends Model
{
    protected $guarded = [];

    public function chantier()
    {
        return $this->belongsTo(Chantiers::class);
    }
}
