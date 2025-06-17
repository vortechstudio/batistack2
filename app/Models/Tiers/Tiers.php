<?php

namespace App\Models\Tiers;

use App\Enums\Tiers\TiersNature;
use App\Enums\Tiers\TiersType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tiers extends Model
{
    /** @use HasFactory<\Database\Factories\Tiers\TiersFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tva' => 'boolean',
            'nature' => TiersNature::class,
            'type' => TiersType::class,
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(TiersAddress::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(TiersContact::class);
    }

    public function fournisseur(): HasOne
    {
        return $this->hasOne(TiersFournisseur::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(TiersClient::class);
    }

    public function getNextId()
    {
        return $this->id ? $this->id+1 : 1;
    }

    public function getNextClientCode(): string
    {
        $cus = "CLT".now()->year."-";
        return $this->id ? $cus.$this->id+1 : $cus."1";
    }

    public function getNextFournisseurCode(): string
    {
        $cus = "FOUR".now()->year."-";
        return $this->id ? $cus.$this->id+1 : $cus."1";
    }
}
