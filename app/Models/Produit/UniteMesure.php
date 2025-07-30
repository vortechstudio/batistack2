<?php

declare(strict_types=1);

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class UniteMesure extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\UniteMesureFactory> */
    use HasFactory;

    protected $table = 'unite_mesures';

    protected $guarded = [];

    /**
     * Relation vers l'unité de base pour conversion
     */
    public function uniteBase(): BelongsTo
    {
        return $this->belongsTo(UniteMesure::class, 'unite_base_id');
    }

    /**
     * Relation vers les unités dérivées
     */
    public function unitesDerivees(): HasMany
    {
        return $this->hasMany(UniteMesure::class, 'unite_base_id');
    }

    /**
     * Relation vers les produits utilisant cette unité
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    /**
     * Convertir une quantité vers l'unité de base
     */
    public function convertirVersBase(float $quantite): float
    {
        return $quantite * $this->facteur_conversion;
    }

    /**
     * Convertir une quantité depuis l'unité de base
     */
    public function convertirDepuisBase(float $quantite): float
    {
        return $this->facteur_conversion > 0 ? $quantite / $this->facteur_conversion : 0;
    }

    /**
     * Convertir vers une autre unité
     */
    public function convertirVers(float $quantite, UniteMesure $uniteDestination): float
    {
        if ($this->id === $uniteDestination->id) {
            return $quantite;
        }

        // Convertir vers la base puis vers l'unité destination
        $quantiteBase = $this->convertirVersBase($quantite);
        
        return $uniteDestination->convertirDepuisBase($quantiteBase);
    }

    /**
     * Obtenir le nom complet avec symbole
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' (' . $this->symbole . ')';
    }

    /**
     * Scope pour les unités actives
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope par type d'unité
     */
    public function scopeParType($query, string $type)
    {
        return $query->where('type', $type);
    }

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'facteur_conversion' => 'decimal:6',
            'metadata' => 'array',
        ];
    }
}