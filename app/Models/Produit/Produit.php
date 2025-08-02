<?php

namespace App\Models\Produit;

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
    ];

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
     * Scope pour les produits (pas les services)
     */
    public function scopeProduits($query)
    {
        return $query->where('type', 'produit');
    }

    /**
     * Scope pour les services
     */
    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    /**
     * Génère une référence unique pour le produit
     */
    public static function generateReference(string $type = 'produit'): string
    {
        $prefix = $type === 'service' ? 'SRV' : 'PRD';
        $number = str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);

        return $prefix . '-' . $number;
    }

    /**
     * Vérifie si le produit est un service
     */
    public function isService(): bool
    {
        return $this->type === 'service';
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
}
