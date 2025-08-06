<?php

namespace App\Livewire\Produit\Produit;

use App\Models\Produit\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProduitShow extends Component
{
    public Produit $produit;

    public function mount(int $id)
    {
        $this->produit = Produit::findOrFail($id);
    }

    #[Title('Détail du produit')]
    #[Layout('components.layouts.produit')]
    public function render()
    {
        return view('livewire.produit.produit.produit-show');
    }
}
