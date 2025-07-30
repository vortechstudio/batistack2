<?php

declare(strict_types=1);

namespace App\Models\Produit;

use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TarifClient extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\TarifClientFactory> */
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
     * Relation vers le client (tiers)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Tiers::class, 'tiers_id');
    }

    /**
     * Calculer le prix de vente avec quantité
     */
    public function calculerPrixVente(float $quantite): float
    {
        $prix = $this->prix_vente;

        // Appliquer les tarifs dégressifs
        if ($this->seuil_quantite_3 && $quantite >= $this->seuil_quantite_3 && $this->prix_quantite_3) {
            $prix = $this->prix_quantite_3;
        } elseif ($this->seuil_quantite_2 && $quantite >= $this->seuil_quantite_2 && $this->prix_quantite_2) {
            $prix = $this->prix_quantite_2;
        } elseif ($this->seuil_quantite_1 && $quantite >= $this->seuil_quantite_1 && $this->prix_quantite_1) {
            $prix = $this->prix_quantite_1;
        }

        // Appliquer les remises
        if ($this->remise_volume && $this->seuil_remise_volume && $quantite >= $this->seuil_remise_volume) {
            $prix *= (1 - $this->remise_volume / 100);
        }

        if ($this->remise_commerciale) {
            $prix *= (1 - $this->remise_commerciale / 100);
        }

        if ($this->remise_fidelite) {
            $prix *= (1 - $this->remise_fidelite / 100);
        }

        return $prix;
    }

    /**
     * Calculer le total avec frais
     */
    public function calculerTotal(float $quantite): float
    {
        $prixVente = $this->calculerPrixVente($quantite);
        $total = $prixVente * $quantite;

        // Ajouter les frais de livraison si applicable
        if ($this->frais_livraison_fixe) {
            $total += $this->frais_livraison_fixe;
        }

        if ($this->frais_livraison_pourcentage) {
            $total += ($total * $this->frais_livraison_pourcentage / 100);
        }

        return $total;
    }

    /**
     * Calculer la marge en pourcentage
     */
    public function calculerMargePourcentage(?float $prixAchat = null): ?float
    {
        if (!$prixAchat) {
            $tarifFournisseur = $this->produit->getTarifFournisseurActif();
            if (!$tarifFournisseur) {
                return null;
            }
            $prixAchat = $tarifFournisseur->prix_achat;
        }

        if ($prixAchat <= 0) {
            return null;
        }

        return (($this->prix_vente - $prixAchat) / $prixAchat) * 100;
    }

    /**
     * Calculer la marge en valeur
     */
    public function calculerMargeValeur(?float $prixAchat = null): ?float
    {
        if (!$prixAchat) {
            $tarifFournisseur = $this->produit->getTarifFournisseurActif();
            if (!$tarifFournisseur) {
                return null;
            }
            $prixAchat = $tarifFournisseur->prix_achat;
        }

        return $this->prix_vente - $prixAchat;
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
     * Obtenir le prix minimum applicable
     */
    public function getPrixMinimum(): ?float
    {
        return $this->prix_minimum;
    }

    /**
     * Obtenir le prix maximum applicable
     */
    public function getPrixMaximum(): ?float
    {
        return $this->prix_maximum;
    }

    /**
     * Vérifier si le prix est dans la fourchette autorisée
     */
    public function prixDansFourchette(float $prix): bool
    {
        if ($this->prix_minimum && $prix < $this->prix_minimum) {
            return false;
        }

        if ($this->prix_maximum && $prix > $this->prix_maximum) {
            return false;
        }

        return true;
    }

    /**
     * Obtenir le délai de livraison en jours
     */
    public function getDelaiLivraisonJours(): ?int
    {
        return $this->delai_livraison;
    }

    /**
     * Vérifier si le tarif est négociable
     */
    public function estNegociable(): bool
    {
        return $this->negociable ?? false;
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
     * Scope par client
     */
    public function scopeParClient($query, Tiers|int $client)
    {
        $clientId = $client instanceof Tiers ? $client->id : $client;
        return $query->where('tiers_id', $clientId);
    }

    /**
     * Scope par type de client
     */
    public function scopeParTypeClient($query, string $typeClient)
    {
        return $query->where('type_client', $typeClient);
    }

    /**
     * Scope par priorité
     */
    public function scopeParPriorite($query)
    {
        return $query->orderBy('priorite');
    }

    /**
     * Scope pour les tarifs généraux (sans client spécifique)
     */
    public function scopeGeneral($query)
    {
        return $query->whereNull('tiers_id')->whereNull('type_client');
    }

    /**
     * Scope pour les tarifs négociables
     */
    public function scopeNegociable($query)
    {
        return $query->where('negociable', true);
    }

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'prix_vente' => 'decimal:2',
            'devise' => 'string',
            'marge_pourcentage' => 'decimal:2',
            'marge_valeur' => 'decimal:2',
            'quantite_minimale' => 'decimal:3',
            'seuil_quantite_1' => 'decimal:3',
            'seuil_quantite_2' => 'decimal:3',
            'seuil_quantite_3' => 'decimal:3',
            'prix_quantite_1' => 'decimal:2',
            'prix_quantite_2' => 'decimal:2',
            'prix_quantite_3' => 'decimal:2',
            'remise_volume' => 'decimal:2',
            'seuil_remise_volume' => 'decimal:3',
            'remise_commerciale' => 'decimal:2',
            'remise_fidelite' => 'decimal:2',
            'frais_livraison_fixe' => 'decimal:2',
            'frais_livraison_pourcentage' => 'decimal:2',
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
            'zones_intervention' => 'array',
            'prix_forfait' => 'decimal:2',
            'prix_minimum' => 'decimal:2',
            'prix_maximum' => 'decimal:2',
            'negociable' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
