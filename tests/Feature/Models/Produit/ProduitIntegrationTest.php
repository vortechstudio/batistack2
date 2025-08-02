<?php

declare(strict_types=1);

use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\TarifClient;

describe('Intégration des modèles Produit', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create(['name' => 'Matériaux']);
        $this->entrepot = Entrepot::factory()->create(['name' => 'Entrepôt Principal']);
        $this->produit = Produit::factory()->create([
            'category_id' => $this->category->id,
            'entrepot_id' => $this->entrepot->id,
            'limit_stock' => 10,
            'optimal_stock' => 50,
        ]);
    });

    it('peut créer un produit complet avec ses relations', function () {
        expect($this->produit->category->name)->toBe('Matériaux')
            ->and($this->produit->entrepot->name)->toBe('Entrepôt Principal');
    });

    it('peut gérer le stock d\'un produit', function () {
        $stock = ProduitStock::factory()->create([
            'produit_id' => $this->produit->id,
            'entrepot_id' => $this->entrepot->id,
            'quantite' => 25,
        ]);

        expect($stock->produit->id)->toBe($this->produit->id)
            ->and($stock->entrepot->id)->toBe($this->entrepot->id)
            ->and($stock->getStatutStock())->toBe('faible'); // 25 < 50 (optimal)
    });

    it('peut créer des tarifs pour un produit', function () {
        $tarif = TarifClient::factory()->create([
            'produit_id' => $this->produit->id,
            'prix_unitaire' => 99.99,
            'taux_tva' => 20.0,
        ]);

        expect($tarif->produit->id)->toBe($this->produit->id)
            ->and($tarif->prix_ttc)->toBe(119.99)
            ->and($tarif->isProduit())->toBeTrue();
    });

    it('peut filtrer les produits par différents critères', function () {
        // Créer des produits avec différentes caractéristiques
        Produit::factory()->create(['achat' => false, 'vente' => true]);
        Produit::factory()->create(['achat' => true, 'vente' => false]);
        Produit::factory()->create([
            'achat' => true,
            'vente' => true,
            'stock_actuel' => 5,
            'limit_stock' => 10,
        ]);

        $produitsAchat = Produit::disponibleAchat()->count();
        $produitsVente = Produit::disponibleVente()->count();
        $produitsStockFaible = Produit::stockFaible()->count();

        expect($produitsAchat)->toBe(2) // Le produit initial + celui avec achat=true
            ->and($produitsVente)->toBe(2) // Le produit initial + celui avec vente=true
            ->and($produitsStockFaible)->toBe(1); // Seulement celui avec stock_actuel=5
    });
});
