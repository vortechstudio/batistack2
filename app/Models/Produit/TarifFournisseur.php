<?php

declare(strict_types=1);

namespace App\Models\Produit;

use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TarifFournisseur extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\TarifFournisseurFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Relation vers le produit
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Relation vers le fournisseur (tiers)
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Tiers::class, 'tiers_id');
    }

    /**
     * Calculer le prix d'achat avec quantité
     */
    public function calculerPrixAchat(float $quantite): float
    {
        $prix = $this->prix_achat;

        // Appliquer les remises par quantité
        if ($this->seuil_quantite_3 && $quantite >= $this->seuil_quantite_3 && $this->remise_quantite_3) {
            $prix *= (1 - $this->remise_quantite_3 / 100);
        } elseif ($this->seuil_quantite_2 && $quantite >= $this->seuil_quantite_2 && $this->remise_quantite_2) {
            $prix *= (1 - $this->remise_quantite_2 / 100);
        } elseif ($this->seuil_quantite_1 && $quantite >= $this->seuil_quantite_1 && $this->remise_quantite_1) {
            $prix *= (1 - $this->remise_quantite_1 / 100);
        }

        // Appliquer la remise générale
        if ($this->remise_generale) {
            $prix *= (1 - $this->remise_generale / 100);
        }

        return $prix;
    }

    /**
     * Calculer le coût total avec frais
     */
    public function calculerCoutTotal(float $quantite): float
    {
        $prixAchat = $this->calculerPrixAchat($quantite);
        $total = $prixAchat * $quantite;

        // Ajouter les frais de port si applicable
        if ($this->frais_port_fixe) {
            $total += $this->frais_port_fixe;
        }

        if ($this->frais_port_pourcentage) {
            $total += ($total * $this->frais_port_pourcentage / 100);
        }

        return $total;
    }

    /**
     * Vérifier si le tarif est valide à une date donnée
     */
    public function estValide(?\DateTime $date = null): bool
    {
        $date = $date ?: now();

        if (!$this->actif) {
            return false;
        }

        if ($this->date_debut && $date < $this->date_debut) {
            return false;
        }

        if ($this->date_fin && $date > $this->date_fin) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si la quantité respecte la quantité minimale
     */
    public function respecteQuantiteMinimale(float $quantite): bool
    {
        return !$this->quantite_minimale || $quantite >= $this->quantite_minimale;
    }

    /**
     * Obtenir le délai de livraison en jours
     */
    public function getDelaiLivraisonJours(): ?int
    {
        return $this->delai_livraison;
    }

    /**
     * Obtenir les conditions de paiement formatées
     */
    public function getConditionsPaiementFormatees(): ?string
    {
        return $this->conditions_paiement;
    }

    /**
     * Scope pour les tarifs actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les tarifs valides à une date
     */
    public function scopeValide($query, ?\DateTime $date = null)
    {
        $date = $date ?: now();

        return $query->where('actif', true)
            ->where('date_debut', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', $date);
            });
    }

    /**
     * Scope par fournisseur
     */
    public function scopeParFournisseur($query, Tiers|int $fournisseur)
    {
        $fournisseurId = $fournisseur instanceof Tiers ? $fournisseur->id : $fournisseur;
        return $query->where('tiers_id', $fournisseurId);
    }

    /**
     * Scope par priorité
     */
    public function scopeParPriorite($query)
    {
        return $query->orderBy('priorite')->orderBy('prefere', 'desc');
    }

    /**
     * Scope pour les tarifs préférés
     */
    public function scopePrefere($query)
    {
        return $query->where('prefere', true);
    }

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'prefere' => 'boolean',
            'prix_achat' => 'decimal:2',
            'quantite_minimale' => 'decimal:3',
            'seuil_quantite_1' => 'decimal:3',
            'seuil_quantite_2' => 'decimal:3',
            'seuil_quantite_3' => 'decimal:3',
            'remise_quantite_1' => 'decimal:2',
            'remise_quantite_2' => 'decimal:2',
            'remise_quantite_3' => 'decimal:2',
            'remise_generale' => 'decimal:2',
            'frais_port_fixe' => 'decimal:2',
            'frais_port_pourcentage' => 'decimal:2',
            'delai_livraison' => 'integer',
            'priorite' => 'integer',
            'date_debut' => 'date',
            'date_fin' => 'date',
            'tarif_horaire' => 'decimal:2',
            'cout_deplacement' => 'decimal:2',
            'majoration_weekend' => 'decimal:2',
            'majoration_nuit' => 'decimal:2',
            'majoration_urgence' => 'decimal:2',
            'grille_tarifaire' => 'array',
            'metadata' => 'array',
        ];
    }
}
