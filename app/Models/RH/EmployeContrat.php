<?php

namespace App\Models\RH;

use App\Enums\RH\StatusContrat;
use App\Enums\RH\TypeContrat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeContrat extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'type' => TypeContrat::class,
            'status' => StatusContrat::class,
            'signed_start_at' => 'datetime',
        ];
    }

    public function getNombreJourTravailAttribute()
    {
        return $this->employe->pointages->count();
    }
}
