<?php

namespace App\Actions\Produit;

use App\Models\Produit\Produit;
use Illuminate\Support\Facades\DB;

class NewProduct
{
    public function handle(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Création du produit
            $produit = Produit::create([
                'name' => $data['name'],
                'achat' => $data['achat'] ?? false,
                'vente' => $data['vente'] ?? false,
                'description' => $data['description'] ?? null,
                'serial_number' => $data['serial_number'] ?? null,
                'limit_stock' => $data['limit_stock'] ?? 0,
                'optimal_stock' => $data['optimal_stock'] ?? 0,
                'poids_value' => $data['poids_value'] ?? null,
                'poids_unite' => $data['poids_unite'] ?? null,
                'longueur' => $data['longueur'] ?? null,
                'largeur' => $data['largeur'] ?? null,
                'hauteur' => $data['hauteur'] ?? null,
                'llh_unite' => $data['llh_unite'] ?? null,
                'category_id' => $data['category_id'],
                'entrepot_id' => $data['entrepot_id'],
                'code_comptable_vente' => $data['code_comptable_vente'] ?? null,
            ]);

            // Création des stocks initiaux
            if (isset($data['stockInitial']) && is_array($data['stockInitial'])) {
                foreach ($data['stockInitial'] as $stock) {
                    $produit->stocks()->create([
                        'entrepot_id' => $stock['entrepot_id'],
                        'quantite' => $stock['quantite'] ?? 0,
                        'produit_id' => $produit->id,
                    ]);
                }
            }

            // Création des tarifs client
            if (isset($data['tarifClient']) && is_array($data['tarifClient'])) {
                foreach ($data['tarifClient'] as $tarif) {
                    $produit->tarifsClient()->create([
                        'prix_unitaire' => $tarif['prix_unitaire'],
                        'taux_tva' => $tarif['taux_tva'],
                        'produit_id' => $produit->id,
                    ]);
                }
            }

            // Création des tarifs fournisseur
            if (isset($data['tarifFournisseur']) && is_array($data['tarifFournisseur'])) {
                foreach ($data['tarifFournisseur'] as $tarif) {
                    $produit->tarifsFournisseur()->create([
                        'produit_id' => $produit->id,
                        'ref_fournisseur' => $tarif['ref_fournisseur'],
                        'qte_minimal' => $tarif['qte_minimal'] ?? 1,
                        'prix_unitaire' => $tarif['prix_unitaire'] ?? 0,
                        'delai_livraison' => $tarif['delai_livraison'] ?? 1,
                        'barrecode' => $tarif['barrecode'] ?? null,
                    ]);
                }
            }

            return $produit;
        });
    }
}
