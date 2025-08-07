<?php

namespace App\Livewire\Produit\Components\Card;

use App\Models\Produit\Produit;
use Livewire\Component;

class ProductCard extends Component
{
    public Produit $produit;
    
    public function render()
    {
        return view('livewire.produit.components.card.product-card');
    }
}
