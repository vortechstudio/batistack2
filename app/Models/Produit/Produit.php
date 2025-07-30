<?php

declare(strict_types=1);

namespace App\Models\Produit;

use App\Enums\Produits\TypeProduit;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Produit extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\Produit\ProduitFactory> */
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    /**
     * Relation vers la catégorie
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategoryProduit::class, 'category_produit_id');
    }

    /**
     * Relation vers l'unité de mesure
     */
    public function uniteMesure(): BelongsTo
    {
        return $this->belongsTo(UniteMesure::class);
    }

    /**
     * Relation vers les tarifs fournisseurs
     */
    public function tarifsFournisseurs(): HasMany
    {
        return $this->hasMany(TarifFournisseur::class);
    }

    /**
     * Relation vers les tarifs clients
     */
    public function tarifsClients(): HasMany
    {
        return $this->hasMany(TarifClient::class);
    }

    /**
     * Obtenir le tarif fournisseur actif le plus prioritaire
     */
    public function getTarifFournisseurActif(?Tiers $fournisseur = null)
    {
        $query = $this->tarifsFournisseurs()
            ->where('actif', true)
            ->where('date_debut', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', now());
            })
            ->orderBy('priorite')
            ->orderBy('prefere', 'desc');

        if ($fournisseur) {
            $query->where('tiers_id', $fournisseur->id);
        }

        return $query->first();
    }

    /**
     * Obtenir le tarif client actif
     */
    public function getTarifClientActif(?Tiers $client = null, ?string $typeClient = null)
    {
        $query = $this->tarifsClients()
            ->where('actif', true)
            ->where('date_debut', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', now());
            })
            ->orderBy('priorite');

        // Tarif spécifique client en priorité
        if ($client) {
            $query->where(function ($q) use ($client, $typeClient) {
                $q->where('tiers_id', $client->id)
                  ->orWhere(function ($subQ) use ($typeClient) {
                      $subQ->whereNull('tiers_id');
                      if ($typeClient) {
                          $subQ->where('type_client', $typeClient);
                      }
                  });
            });
        } elseif ($typeClient) {
            $query->where('type_client', $typeClient)
                  ->whereNull('tiers_id');
        } else {
            $query->whereNull('tiers_id')
                  ->whereNull('type_client');
        }

        return $query->first();
    }

    /**
     * Calculer le prix de vente avec quantité
     */
    public function calculerPrixVente(float $quantite, ?Tiers $client = null, ?string $typeClient = null): ?float
    {
        $tarif = $this->getTarifClientActif($client, $typeClient);
        
        if (!$tarif) {
            return null;
        }

        $prix = $tarif->prix_vente;

        // Appliquer les tarifs dégressifs
        if ($tarif->seuil_quantite_3 && $quantite >= $tarif->seuil_quantite_3 && $tarif->prix_quantite_3) {
            $prix = $tarif->prix_quantite_3;
        } elseif ($tarif->seuil_quantite_2 && $quantite >= $tarif->seuil_quantite_2 && $tarif->prix_quantite_2) {
            $prix = $tarif->prix_quantite_2;
        } elseif ($tarif->seuil_quantite_1 && $quantite >= $tarif->seuil_quantite_1 && $tarif->prix_quantite_1) {
            $prix = $tarif->prix_quantite_1;
        }

        // Appliquer les remises
        if ($tarif->remise_volume && $tarif->seuil_remise_volume && $quantite >= $tarif->seuil_remise_volume) {
            $prix *= (1 - $tarif->remise_volume / 100);
        }

        if ($tarif->remise_commerciale) {
            $prix *= (1 - $tarif->remise_commerciale / 100);
        }

        if ($tarif->remise_fidelite) {
            $prix *= (1 - $tarif->remise_fidelite / 100);
        }

        return $prix;
    }

    /**
     * Vérifier si le produit est un service
     */
    public function estService(): bool
    {
        return TypeProduit::from($this->type)->isService();
    }

    /**
     * Vérifier si le produit est un matériel
     */
    public function estMateriel(): bool
    {
        return TypeProduit::from($this->type)->isMateriel();
    }

    /**
     * Obtenir le nom complet avec référence
     */
    public function getNomCompletAttribute(): string
    {
        return $this->reference . ' - ' . $this->nom;
    }

    /**
     * Obtenir la description pour affichage
     */
    public function getDescriptionAffichageAttribute(): string
    {
        return $this->description_courte ?: $this->description ?: $this->nom;
    }

    /**
     * Scope pour les produits actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les produits vendables
     */
    public function scopeVendable($query)
    {
        return $query->where('vente', true)->where('actif', true);
    }

    /**
     * Scope pour les produits achetables
     */
    public function scopeAchetable($query)
    {
        return $query->where('achat', true)->where('actif', true);
    }

    /**
     * Scope par type de produit
     */
    public function scopeParType($query, TypeProduit|string $type)
    {
        $typeValue = $type instanceof TypeProduit ? $type->value : $type;
        return $query->where('type', $typeValue);
    }

    /**
     * Scope pour les services
     */
    public function scopeServices($query)
    {
        return $query->whereIn('type', array_map(fn($type) => $type->value, TypeProduit::getServices()));
    }

    /**
     * Scope pour les matériels
     */
    public function scopeMateriels($query)
    {
        return $query->whereIn('type', array_map(fn($type) => $type->value, TypeProduit::getMateriels()));
    }

    /**
     * Scope par catégorie
     */
    public function scopeParCategorie($query, CategoryProduit|int $categorie)
    {
        $categorieId = $categorie instanceof CategoryProduit ? $categorie->id : $categorie;
        return $query->where('category_produit_id', $categorieId);
    }

    protected function casts(): array
    {
        return [
            'type' => TypeProduit::class,
            'actif' => 'boolean',
            'achat' => 'boolean',
            'vente' => 'boolean',
            'poids' => 'decimal:3',
            'duree_vie' => 'decimal:2',
            'duree_standard' => 'decimal:2',
            'cout_deplacement' => 'decimal:2',
            'delai_intervention' => 'integer',
            'deplacement_inclus' => 'boolean',
            'urgence_possible' => 'boolean',
            'majoration_urgence' => 'decimal:2',
            'dimensions' => 'array',
            'horaires_disponibilite' => 'array',
            'zones_intervention' => 'array',
            'certifications' => 'array',
            'documents_joints' => 'array',
            'metadata' => 'array',
        ];
    }
}
