<?php

namespace App\Models\RH\Paie;

use Illuminate\Database\Eloquent\Model;

class RubriquePaie extends Model
{
    public $timestamps = false;
    protected $guarded = [];


    public function profils()
    {
        return $this->belongsToMany(ProfilPaie::class, 'profil_rubrique', 'rubrique_paie_id', 'profil_paie_id');
    }

    protected function casts(): array
    {
        return [
            'imposable' => 'boolean',
            'soumis_cotisation' => 'boolean',
        ];
    }
}
