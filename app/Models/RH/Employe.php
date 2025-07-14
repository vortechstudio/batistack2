<?php

namespace App\Models\RH;

use App\Enums\RH\StatusEmploye;
use App\Enums\RH\TypeContrat;
use App\Models\Chantiers\ChantierRessources;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        return $this->hasOne(EmployeInfo::class);
    }

    public function contrat()
    {
        return $this->hasOne(EmployeContrat::class);
    }

    public function ressources()
    {
        return $this->hasMany(ChantierRessources::class);
    }

    protected function casts(): array
    {
        return [
            'date_embauche' => 'date',
            'date_sortie' => 'date',
            'type_contrat' => TypeContrat::class,
            'status' => StatusEmploye::class,
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->nom." ".$this->prenom;
    }
}
