<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Enums\RH\StatusContrat;
use App\Enums\RH\TypeContrat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property StatusContrat $status
 * @property TypeContrat $type
 * @property Carbon|null $signed_start_at
 * @property Carbon $date_debut
 * @property Carbon|null $date_fin
 */
final class EmployeContrat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function getNombreJourTravailAttribute()
    {
        return $this->employe->pointages->count();
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
}
