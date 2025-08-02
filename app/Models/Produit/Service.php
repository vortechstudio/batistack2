<?php

namespace App\Models\Produit;

use App\Models\Core\PlanComptable;
use App\Models\Produit\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\ServiceFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation avec le code comptable de vente
     */
    public function codeComptableVente(): BelongsTo
    {
        return $this->belongsTo(PlanComptable::class, 'code_comptable_vente');
    }

    /**
     * Génère une référence unique pour le service
     */
    public static function generateReference(): string
    {
        $prefix = 'SRV';
        $number = str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);

        return $prefix . '-' . $number;
    }

    /**
     * Scope pour les services d'une catégorie spécifique
     */
    public function scopeParCategorie($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
