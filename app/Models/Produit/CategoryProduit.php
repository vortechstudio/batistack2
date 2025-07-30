<?php

declare(strict_types=1);

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class CategoryProduit extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\CategoryProduitFactory> */
    use HasFactory;

    protected $table = 'category_produits';

    protected $guarded = [];

    /**
     * Relation vers la catégorie parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CategoryProduit::class, 'parent_id');
    }

    /**
     * Relation vers les catégories enfants
     */
    public function enfants(): HasMany
    {
        return $this->hasMany(CategoryProduit::class, 'parent_id')->orderBy('ordre');
    }

    /**
     * Relation vers tous les descendants (récursif)
     */
    public function descendants(): HasMany
    {
        return $this->enfants()->with('descendants');
    }

    /**
     * Relation vers les produits de cette catégorie
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    /**
     * Obtenir le chemin complet de la catégorie
     */
    public function getCheminCompletAttribute(): string
    {
        $chemin = collect();
        $categorie = $this;

        while ($categorie) {
            $chemin->prepend($categorie->nom);
            $categorie = $categorie->parent;
        }

        return $chemin->implode(' > ');
    }

    /**
     * Obtenir le niveau de profondeur
     */
    public function getNiveauAttribute(): int
    {
        $niveau = 0;
        $categorie = $this->parent;

        while ($categorie) {
            $niveau++;
            $categorie = $categorie->parent;
        }

        return $niveau;
    }

    /**
     * Vérifier si c'est une catégorie racine
     */
    public function estRacine(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Vérifier si c'est une catégorie feuille (sans enfants)
     */
    public function estFeuille(): bool
    {
        return $this->enfants()->count() === 0;
    }

    /**
     * Obtenir tous les ancêtres
     */
    public function getAncetres()
    {
        $ancetres = collect();
        $categorie = $this->parent;

        while ($categorie) {
            $ancetres->prepend($categorie);
            $categorie = $categorie->parent;
        }

        return $ancetres;
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les catégories racines
     */
    public function scopeRacine($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope ordonné par ordre puis nom
     */
    public function scopeOrdonne($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'ordre' => 'integer',
            'metadata' => 'array',
        ];
    }
}
