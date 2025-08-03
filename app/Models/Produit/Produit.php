<?php

declare(strict_types=1);

namespace App\Models\Produit;

use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Models\Core\PlanComptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Produit extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\ProduitFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'achat' => 'boolean',
        'vente' => 'boolean',
        'limit_stock' => 'float',
        'optimal_stock' => 'float',
        'poids_value' => 'float',
        'poids_unite' => UnitePoids::class,
        'longueur' => 'float',
        'largeur' => 'float',
        'hauteur' => 'float',
        'llh_unite' => UniteMesure::class,
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($produit) {
            if(empty($produit->reference)) {
                $produit->reference = self::generateReference();
            }
        });
    }

    /**
     * Génère une référence unique pour le produit
     */
    public static function generateReference(): string
    {
        $prefix = 'PRD';
        $number = str_pad((string)(self::count() + 1), 6, '0', STR_PAD_LEFT);

        return $prefix.'-'.$number;
    }

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation avec l'entrepôt
     */
    public function entrepot(): BelongsTo
    {
        return $this->belongsTo(Entrepot::class);
    }

    /**
     * Relation avec le code comptable de vente
     */
    public function codeComptableVente(): BelongsTo
    {
        return $this->belongsTo(PlanComptable::class, 'code_comptable_vente');
    }

    public function tarifClient(): HasOne
    {
        return $this->hasOne(TarifClient::class);
    }

    public function tarifFournisseur(): HasOne
    {
        return $this->hasOne(TarifFournisseur::class);
    }

    /**
     * Relation avec les stocks
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ProduitStock::class);
    }

    /**
     * Obtenir le stock principal (premier stock trouvé)
     */
    public function stockPrincipal(): HasOne
    {
        return $this->hasOne(ProduitStock::class);
    }

    /**
     * Scope pour les produits disponibles à l'achat
     */
    public function scopeDisponibleAchat($query)
    {
        return $query->where('achat', true);
    }

    /**
     * Scope pour les produits disponibles à la vente
     */
    public function scopeDisponibleVente($query)
    {
        return $query->where('vente', true);
    }

    /**
     * Vérifie si le produit est disponible à l'achat
     */
    public function isDisponibleAchat(): bool
    {
        return $this->achat;
    }

    /**
     * Vérifie si le produit est disponible à la vente
     */
    public function isDisponibleVente(): bool
    {
        return $this->vente;
    }

    /**
     * Calcule le volume du produit
     */
    public function getVolumeAttribute(): float
    {
        return $this->longueur * $this->largeur * $this->hauteur;
    }

    /**
     * Retourne le poids formaté avec l'unité
     */
    public function getPoidsFormateAttribute(): string
    {
        return $this->poids_value.' '.$this->poids_unite->symbol();
    }

    public function getPrixUnitaireTarifAttribute()
    {
        return $this->tarifClient->prix_unitaire ?? 0.0;
    }

    /**
     * Retourne les dimensions formatées
     */
    public function getDimensionsFormateesAttribute(): string
    {
        if ($this->longueur === 0.0 && $this->largeur === 0.0 && $this->hauteur === 0.0) {
            return 'Non spécifié';
        }

        return sprintf(
            '%s x %s x %s %s',
            $this->longueur,
            $this->largeur,
            $this->hauteur,
            $this->llh_unite->symbol()
        );
    }
}
