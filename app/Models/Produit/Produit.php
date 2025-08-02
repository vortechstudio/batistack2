<?php

namespace App\Models\Produit;

use App\Enums\Produits\TypeProduit;
use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Models\Core\PlanComptable;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produit extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\ProduitFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'achat' => 'boolean',
        'vente' => 'boolean',
        'limit_stock' => 'decimal:2',
        'optimal_stock' => 'decimal:2',
        'poids_value' => 'decimal:2',
        'poids_unite' => UnitePoids::class,
        'longueur' => 'decimal:2',
        'largeur' => 'decimal:2',
        'hauteur' => 'decimal:2',
        'llh_unite' => UniteMesure::class,
    ];

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
     * Scope pour les produits avec stock faible
     */
    public function scopeStockFaible($query)
    {
        return $query->whereColumn('stock_actuel', '<=', 'limit_stock');
    }

    /**
     * Génère une référence unique pour le produit
     */
    public static function generateReference(): string
    {
        $prefix = "PRD";
        $number = str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);

        return $prefix . '-' . $number;
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
     * Vérifie si le stock est faible
     */
    public function isStockFaible(): bool
    {
        return $this->stock_actuel <= $this->limit_stock;
    }

    /**
     * Retourne le poids formaté avec l'unité
     */
    public function getPoidsFormateAttribute(): string
    {
        return $this->poids_value . ' ' . $this->poids_unite->symbol();
    }

    /**
     * Retourne les dimensions formatées
     */
    public function getDimensionsFormateesAttribute(): string
    {
        if ($this->longueur == 0 && $this->largeur == 0 && $this->hauteur == 0) {
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
