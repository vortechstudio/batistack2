<?php

declare(strict_types=1);

namespace App\Models\RH\Paie;

use Illuminate\Database\Eloquent\Model;

final class ProfilPaie extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function rubriques()
    {
        return $this->belongsToMany(RubriquePaie::class, 'profil_rubrique', 'profil_paie_id', 'rubrique_paie_id');
    }
}
