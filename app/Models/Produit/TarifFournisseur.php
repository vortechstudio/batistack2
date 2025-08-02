<?php

namespace App\Models\Produit;

use App\Models\Produit\Produit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifFournisseur extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\TarifFournisseurFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'qte_minimal' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'delai_livraison' => 'integer',
    ];

    /**
     * Relation avec le produit
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Scope pour les tarifs avec délai de livraison rapide
     */
    public function scopeLivraisonRapide($query, int $maxJours = 7)
    {
        return $query->where('delai_livraison', '<=', $maxJours);
    }

    /**
     * Scope pour les tarifs avec quantité minimale faible
     */
    public function scopeQuantiteMinimaleFaible($query, float $maxQuantite = 10)
    {
        return $query->where('qte_minimal', '<=', $maxQuantite);
    }

    /**
     * Scope pour les tarifs par gamme de prix
     */
    public function scopePrixEntre($query, float $min, float $max)
    {
        return $query->whereBetween('prix_unitaire', [$min, $max]);
    }

    /**
     * Génère une référence fournisseur unique
     */
    public static function generateRefFournisseur(string $prefix = 'REF'): string
    {
        $number = str_pad(static::count() + 1, 8, '0', STR_PAD_LEFT);
        return $prefix . '-' . $number;
    }

    /**
     * Calcule le prix total pour une quantité donnée
     */
    public function calculerPrixTotal(float $quantite): float
    {
        if ($quantite < $this->qte_minimal) {
            throw new \InvalidArgumentException("La quantité doit être supérieure ou égale à {$this->qte_minimal}");
        }

        return $this->prix_unitaire * $quantite;
    }

    /**
     * Vérifie si la quantité respecte le minimum
     */
    public function quantiteValide(float $quantite): bool
    {
        return $quantite >= $this->qte_minimal;
    }

    /**
     * Retourne le délai de livraison formaté
     */
    public function getDelaiFormateAttribute(): string
    {
        if ($this->delai_livraison <= 1) {
            return $this->delai_livraison . ' jour';
        }

        return $this->delai_livraison . ' jours';
    }

    /**
     * Retourne le prix unitaire formaté
     */
    public function getPrixFormateAttribute(): string
    {
        return number_format($this->prix_unitaire, 2, ',', ' ') . ' €';
    }
}
