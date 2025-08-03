<?php

namespace App\Actions\Produit;

use App\Models\Produit\Produit;

class NewProduct
{
    public function handle(array $data)
    {
        // Création du produit
        $produit = Produit::create([
            'name' => $data['name'],
            'achat' => $data['achat'],
            'vente' => $data['vente'],
            'description' => $data['description'],
            'serial_number' => $data['serial_number'],
            'limit_stock' => $data['limit_stock'],
            'optimal_stock' => $data['optimal_stock'],
            'poids_value' => $data['poids_value'],
            'poids_unite' => $data['poids_unite'],
            'longueur' => $data['longueur'],
            'largeur' => $data['largeur'],
            'hauteur' => $data['hauteur'],
            'llh_unite' => $data['llh_unite'],
            'category_id' => $data['category_id'],
            'entrepot_id' => $data['entrepot_id'],
            'code_comptable_vente' => $data['code_comptable_vente'],
        ]);

        $produit = $produit->refresh();

        // Création des stocks initiaux
        foreach ($data['stockInitial'] as $stock) {
            $stock = $produit->stocks()->create([
                'entrepot_id' => $stock['entrepot_id'],
                'quantite' => $stock['quantite'],
                'produit_id' => $produit->id,
            ]);
        }

        // Création des tarifs client
        foreach ($data['tarifClient'] as $tarif) {
            $produit->tarifClient()->create([
                'prix_unitaire' => $tarif['prix_unitaire'],
                'taux_tva' => $tarif['taux_tva'],
                'produit_id' => $produit->id,
            ]);
        }

        // Création des tarifs fournisseur
        foreach ($data['tarifFournisseur'] as $tarif) {
            $produit->tarifFournisseur()->create([
                'produit_id' => $produit->id,
                'ref_fournisseur' => $tarif['ref_fournisseur'],
                'qte_minimal' => $tarif['qte_minimal'],
                'prix_unitaire' => $tarif['prix_unitaire'],
                'delai_livraison' => $tarif['delai_livraison'],
                'barrecode' => $tarif['barrecode'],
            ]);
        }

        return $produit;
    }
}
