<?php

declare(strict_types=1);

use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;

beforeEach(function () {
    $this->produit = Produit::factory()->create([
        'limit_stock' => 10,
        'optimal_stock' => 50,
    ]);
    $this->entrepot = Entrepot::factory()->create();
    $this->stock = ProduitStock::factory()->create([
        'produit_id' => $this->produit->id,
        'entrepot_id' => $this->entrepot->id,
        'quantite' => 25,
    ]);
});

describe('ProduitStock Model', function () {
    it('peut être créé avec les attributs requis', function () {
        expect($this->stock)->toBeInstanceOf(ProduitStock::class)
            ->and($this->stock->quantite)->toBe(25)
            ->and($this->stock->produit_id)->toBe($this->produit->id);
    });

    describe('Relations', function () {
        it('appartient à un produit', function () {
            expect($this->stock->produit)->toBeInstanceOf(Produit::class)
                ->and($this->stock->produit->id)->toBe($this->produit->id);
        });

        it('appartient à un entrepôt', function () {
            expect($this->stock->entrepot)->toBeInstanceOf(Entrepot::class)
                ->and($this->stock->entrepot->id)->toBe($this->entrepot->id);
        });
    });

    describe('Scopes', function () {
        it('filtre les stocks en stock', function () {
            ProduitStock::factory()->create(['quantite' => 0]);

            $stocksEnStock = ProduitStock::enStock()->get();

            expect($stocksEnStock)->toHaveCount(1)
                ->and($stocksEnStock->first()->quantite)->toBeGreaterThan(0);
        });

        it('filtre les stocks vides', function () {
            ProduitStock::factory()->create(['quantite' => 0]);

            $stocksVides = ProduitStock::vide()->get();

            expect($stocksVides)->toHaveCount(1)
                ->and($stocksVides->first()->quantite)->toBeLessThanOrEqual(0);
        });

        it('filtre par produit', function () {
            $autreProduit = Produit::factory()->create();
            ProduitStock::factory()->create(['produit_id' => $autreProduit->id]);

            $stocks = ProduitStock::pourProduit($this->produit->id)->get();

            expect($stocks)->toHaveCount(1)
                ->and($stocks->first()->produit_id)->toBe($this->produit->id);
        });
    });

    describe('Méthodes de vérification', function () {
        it('vérifie si le stock est disponible', function () {
            expect($this->stock->isDisponible())->toBeTrue();

            $this->stock->update(['quantite' => 0]);
            expect($this->stock->isDisponible())->toBeFalse();
        });

        it('vérifie si le stock est en rupture', function () {
            expect($this->stock->isEnRupture())->toBeFalse();

            $this->stock->update(['quantite' => 0]);
            expect($this->stock->isEnRupture())->toBeTrue();
        });

        it('vérifie si le stock est sous le seuil limite', function () {
            $this->stock->update(['quantite' => 5]);
            expect($this->stock->isSousSeuilLimite())->toBeTrue();

            $this->stock->update(['quantite' => 15]);
            expect($this->stock->isSousSeuilLimite())->toBeFalse();
        });

        it('vérifie si le stock est sous le seuil optimal', function () {
            $this->stock->update(['quantite' => 30]);
            expect($this->stock->isSousSeuilOptimal())->toBeTrue();

            $this->stock->update(['quantite' => 60]);
            expect($this->stock->isSousSeuilOptimal())->toBeFalse();
        });
    });

    describe('Statut du stock', function () {
        it('retourne le statut "rupture"', function () {
            $this->stock->update(['quantite' => 0]);
            expect($this->stock->getStatutStock())->toBe('rupture');
        });

        it('retourne le statut "critique"', function () {
            $this->stock->update(['quantite' => 5]);
            expect($this->stock->getStatutStock())->toBe('critique');
        });

        it('retourne le statut "faible"', function () {
            $this->stock->update(['quantite' => 30]);
            expect($this->stock->getStatutStock())->toBe('faible');
        });

        it('retourne le statut "normal"', function () {
            $this->stock->update(['quantite' => 60]);
            expect($this->stock->getStatutStock())->toBe('normal');
        });
    });

    describe('Couleurs de statut', function () {
        it('retourne la bonne couleur pour chaque statut', function () {
            $this->stock->update(['quantite' => 0]);
            expect($this->stock->getCouleurStatut())->toBe('red');

            $this->stock->update(['quantite' => 5]);
            expect($this->stock->getCouleurStatut())->toBe('amber');

            $this->stock->update(['quantite' => 30]);
            expect($this->stock->getCouleurStatut())->toBe('blue');

            $this->stock->update(['quantite' => 60]);
            expect($this->stock->getCouleurStatut())->toBe('green');
        });
    });

    describe('Formatage', function () {
        it('formate la quantité', function () {
            expect($this->stock->quantite_formatee)->toBe('25 unités');
        });
    });
});
