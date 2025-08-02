<?php

declare(strict_types=1);

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TarifClient extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\TarifClientFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
    ];

    /**
     * Relation avec le produit (optionnelle)
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Relation avec le service (optionnelle)
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope pour les tarifs de produits
     */
    public function scopePourProduits($query)
    {
        return $query->whereNotNull('produit_id')->whereNull('service_id');
    }

    /**
     * Scope pour les tarifs de services
     */
    public function scopePourServices($query)
    {
        return $query->whereNotNull('service_id')->whereNull('produit_id');
    }

    /**
     * Scope pour les tarifs par gamme de prix HT
     */
    public function scopePrixHTEntre($query, float $min, float $max)
    {
        return $query->whereBetween('prix_unitaire', [$min, $max]);
    }

    /**
     * Scope pour les tarifs avec un taux de TVA spécifique
     */
    public function scopeAvecTauxTVA($query, float $taux)
    {
        return $query->where('taux_tva', $taux);
    }

    /**
     * Calcule le prix TTC
     */
    public function getPrixTTCAttribute(): float
    {
        return $this->prix_unitaire * (1 + $this->taux_tva / 100);
    }

    /**
     * Calcule le montant de la TVA
     */
    public function getMontantTVAAttribute(): float
    {
        return $this->prix_unitaire * ($this->taux_tva / 100);
    }

    /**
     * Calcule le prix total HT pour une quantité donnée
     */
    public function calculerPrixTotalHT(float $quantite): float
    {
        return $this->prix_unitaire * $quantite;
    }

    /**
     * Calcule le prix total TTC pour une quantité donnée
     */
    public function calculerPrixTotalTTC(float $quantite): float
    {
        return $this->calculerPrixTotalHT($quantite) * (1 + $this->taux_tva / 100);
    }

    /**
     * Calcule le montant total de TVA pour une quantité donnée
     */
    public function calculerMontantTotalTVA(float $quantite): float
    {
        return $this->calculerPrixTotalHT($quantite) * ($this->taux_tva / 100);
    }

    /**
     * Vérifie si c'est un tarif pour un produit
     */
    public function isProduit(): bool
    {
        return ! is_null($this->produit_id) && is_null($this->service_id);
    }

    /**
     * Vérifie si c'est un tarif pour un service
     */
    public function isService(): bool
    {
        return ! is_null($this->service_id) && is_null($this->produit_id);
    }

    /**
     * Retourne l'élément tarifé (produit ou service)
     */
    public function getElementTarifeAttribute()
    {
        if ($this->isProduit()) {
            return $this->produit;
        }

        if ($this->isService()) {
            return $this->service;
        }

        return null;
    }

    /**
     * Retourne le nom de l'élément tarifé
     */
    public function getNomElementAttribute(): string
    {
        $element = $this->element_tarife;

        return $element ? $element->name : 'Élément non défini';
    }

    /**
     * Retourne le prix HT formaté
     */
    public function getPrixHTFormateAttribute(): string
    {
        return number_format($this->prix_unitaire, 2, ',', ' ').' € HT';
    }

    /**
     * Retourne le prix TTC formaté
     */
    public function getPrixTTCFormateAttribute(): string
    {
        return number_format($this->prix_ttc, 2, ',', ' ').' € TTC';
    }

    /**
     * Retourne le taux de TVA formaté
     */
    public function getTauxTVAFormateAttribute(): string
    {
        return $this->taux_tva.'%';
    }
}
