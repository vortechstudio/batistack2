<?php

declare(strict_types=1);

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Entrepot extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\EntrepotFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
}
