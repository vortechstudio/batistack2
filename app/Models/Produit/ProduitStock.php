<?php

declare(strict_types=1);

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ProduitStock extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\ProduitStockFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'quantite' => 'integer',
    ];

    /**
     * Relation avec le produit
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Relation avec l'entrepôt
     */
    public function entrepot(): BelongsTo
    {
        return $this->belongsTo(Entrepot::class);
    }

    /**
     * Relation avec les mouvements de stock
     */
    public function mouvements(): HasMany
    {
        return $this->hasMany(ProduitStockMvm::class);
    }

    /**
     * Scope pour les stocks avec quantité positive
     */
    public function scopeEnStock($query)
    {
        return $query->where('quantite', '>', 0);
    }

    /**
     * Scope pour les stocks vides
     */
    public function scopeVide($query)
    {
        return $query->where('quantite', '<=', 0);
    }

    /**
     * Scope pour un produit spécifique
     */
    public function scopePourProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    /**
     * Scope pour un entrepôt spécifique
     */
    public function scopePourEntrepot($query, $entrepotId)
    {
        return $query->where('entrepot_id', $entrepotId);
    }

    /**
     * Vérifier si le stock est disponible
     */
    public function isDisponible(): bool
    {
        return $this->quantite > 0;
    }

    /**
     * Vérifier si le stock est en rupture
     */
    public function isEnRupture(): bool
    {
        return $this->quantite <= 0;
    }

    /**
     * Vérifier si le stock est en dessous du seuil limite
     */
    public function isSousSeuilLimite(): bool
    {
        if (! $this->produit->limit_stock) {
            return false;
        }

        return $this->quantite <= $this->produit->limit_stock;
    }

    /**
     * Vérifier si le stock est en dessous du seuil optimal
     */
    public function isSousSeuilOptimal(): bool
    {
        if (! $this->produit->optimal_stock) {
            return false;
        }

        return $this->quantite <= $this->produit->optimal_stock;
    }

    /**
     * Obtenir le statut du stock
     */
    public function getStatutStock(): string
    {
        if ($this->isEnRupture()) {
            return 'rupture';
        }

        if ($this->isSousSeuilLimite()) {
            return 'critique';
        }

        if ($this->isSousSeuilOptimal()) {
            return 'faible';
        }

        return 'normal';
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getCouleurStatut(): string
    {
        return match ($this->getStatutStock()) {
            'rupture' => 'red',
            'critique' => 'amber',
            'faible' => 'blue',
            'normal' => 'green',
        };
    }

    /**
     * Formater la quantité avec l'unité
     */
    public function getQuantiteFormateeAttribute(): string
    {
        return number_format($this->quantite, 0, ',', ' ').' unités';
    }
}
