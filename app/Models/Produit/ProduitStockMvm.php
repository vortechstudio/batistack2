<?php

declare(strict_types=1);

namespace App\Models\Produit;

use App\Enums\Produits\TypeMouvementStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ProduitStockMvm extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\ProduitStockMvmFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'quantite' => 'integer',
        'type' => TypeMouvementStock::class,
    ];

    /**
     * Générer une référence unique
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'MVM-'.mb_strtoupper(uniqid());
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Relation avec le stock produit
     */
    public function produitStock(): BelongsTo
    {
        return $this->belongsTo(ProduitStock::class);
    }

    /**
     * Relation avec le produit via le stock
     */
    public function produit(): BelongsTo
    {
        return $this->produitStock->produit();
    }

    /**
     * Relation avec l'entrepôt via le stock
     */
    public function entrepot(): BelongsTo
    {
        return $this->produitStock->entrepot();
    }

    /**
     * Scope pour les entrées
     */
    public function scopeEntrees($query)
    {
        return $query->where('type', TypeMouvementStock::ENTREE);
    }

    /**
     * Scope pour les sorties
     */
    public function scopeSorties($query)
    {
        return $query->where('type', TypeMouvementStock::SORTIE);
    }

    /**
     * Scope pour un stock spécifique
     */
    public function scopePourStock($query, $stockId)
    {
        return $query->where('produit_stock_id', $stockId);
    }

    /**
     * Scope pour une période donnée
     */
    public function scopePourPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('created_at', [$dateDebut, $dateFin]);
    }

    /**
     * Scope pour les mouvements récents
     */
    public function scopeRecents($query, $jours = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    /**
     * Vérifier si c'est une entrée
     */
    public function isEntree(): bool
    {
        return $this->type === TypeMouvementStock::ENTREE;
    }

    /**
     * Vérifier si c'est une sortie
     */
    public function isSortie(): bool
    {
        return $this->type === TypeMouvementStock::SORTIE;
    }

    /**
     * Obtenir l'impact sur le stock
     */
    public function getImpactStock(): int
    {
        return $this->quantite * $this->type->getMultiplier();
    }

    /**
     * Obtenir le libellé du type
     */
    public function getTypeLibelleAttribute(): string
    {
        return $this->type->getLabel();
    }

    /**
     * Obtenir l'icône du type
     */
    public function getTypeIconeAttribute(): string
    {
        return $this->type->getIcon();
    }

    /**
     * Formater la quantité avec signe
     */
    public function getQuantiteFormateeAttribute(): string
    {
        $signe = $this->isEntree() ? '+' : '-';

        return $signe.number_format($this->quantite, 0, ',', ' ');
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($mouvement) {
            if (empty($mouvement->reference)) {
                $mouvement->reference = self::generateReference();
            }
        });
    }
}
