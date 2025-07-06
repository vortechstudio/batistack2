<?php

namespace App\Models\RH\Paie;

use Illuminate\Database\Eloquent\Model;

class ProfilPaie extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function rubriques()
    {
        return $this->belongsToMany(RubriquePaie::class, 'profil_rubrique', 'profil_paie_id', 'rubrique_paie_id');
    }
}
