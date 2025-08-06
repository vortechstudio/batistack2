<?php

declare(strict_types=1);

namespace App\Livewire\Produit\Produit;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class ProduitIndex extends Component
{
    #[Title('Liste des produits')]
    #[Layout('components.layouts.produit')]
    public function render()
    {
        return view('livewire.produit.produit.produit-index');
    }
}
