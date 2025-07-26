<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Enums\RH\StatusEmploye;
use App\Enums\RH\TypeContrat;
use App\Models\Chantiers\ChantierRessources;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Employe extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info(): HasOne
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

    public function pointages()
    {
        return $this->hasMany(EmployePointage::class);
    }

    public function bank()
    {
        return $this->hasOne(EmployeBank::class);
    }

    public function notesFrais()
    {
        return $this->hasMany(NoteFrais::class);
    }

    public function notesFraisEnAttente()
    {
        return $this->hasMany(NoteFrais::class)->enAttente();
    }

    public function notesFraisValidees()
    {
        return $this->hasMany(NoteFrais::class)->validees();
    }

    public function getFullNameAttribute()
    {
        return $this->nom.' '.$this->prenom;
    }

    public function getFullAddressAttribute()
    {
        return $this->adresse.', '.$this->code_postal.' '.$this->ville;
    }

    public function getMatriculeAttribute()
    {
        return Str::upper(Str::limit($this->uuid, 8, ''));
    }

    public function getMontantNotesFraisEnAttenteAttribute(): float
    {
        return $this->notesFraisEnAttente->sum('montant_total');
    }

    public function getMontantNotesFraisValideesMoisAttribute(): float
    {
        return $this->notesFraisValidees()
            ->whereMonth('date_validation', now()->month)
            ->whereYear('date_validation', now()->year)
            ->sum('montant_valide');
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
}
