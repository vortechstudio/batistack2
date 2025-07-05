<?php

declare(strict_types=1);

namespace App\Models\Commerce;

use App\Enums\Commerce\StatusFacture;
use App\Enums\Commerce\TypeFacture;
use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Facture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function chantiers(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class);
    }

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function lignes()
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function reglements()
    {
        return $this->hasMany(FacturePaiement::class);
    }

    protected function casts(): array
    {
        return [
            'date_facture' => 'date',
            'date_echeance' => 'date',
            'type_facture' => TypeFacture::class,
            'status' => StatusFacture::class,
        ];
    }
}
