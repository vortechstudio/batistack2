<?php

namespace App\Models\Produit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    /** @use HasFactory<\Database\Factories\Produit\EntrepotFactory> */
    use HasFactory;
    protected $guarded = [];
    public $timestamp = false;
}
