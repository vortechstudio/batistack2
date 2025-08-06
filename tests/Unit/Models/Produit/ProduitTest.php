<?php

declare(strict_types=1);

use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;

beforeEach(function () {
    $this->produit = Produit::factory()->create([
        'name' => 'Produit Test',
        'reference' => 'PRD-000001',
        'achat' => true,
        'vente' => true,
        'limit_stock' => 10.00,
        'optimal_stock' => 50.00,
        'poids_value' => 2.5,
        'poids_unite' => UnitePoids::KILOGRAMME,
        'longueur' => 10.0,
        'largeur' => 5.0,
        'hauteur' => 2.0,
        'llh_unite' => UniteMesure::CENTIMETRE,
    ]);
});

describe('Produit Model', function () {
    it('peut être créé avec les attributs requis', function () {
        expect($this->produit)->toBeInstanceOf(Produit::class)
            ->and($this->produit->name)->toBe('Produit Test')
            ->and($this->produit->reference)->toBe('PRD-000001');
    });

    it('cast correctement les attributs', function () {
        expect($this->produit->achat)->toBeTrue()
            ->and($this->produit->vente)->toBeTrue()
            ->and($this->produit->limit_stock)->toBeFloat()
            ->and($this->produit->poids_unite)->toBeInstanceOf(UnitePoids::class)
            ->and($this->produit->llh_unite)->toBeInstanceOf(UniteMesure::class);
    });

    describe('generateReference()', function () {
        it('génère une référence unique', function () {
            $reference = Produit::generateReference();

            expect($reference)->toStartWith('PRD-')
                ->and(mb_strlen($reference))->toBe(10); // PRD- + 6 chiffres
        });

        it('génère des références séquentielles', function () {
            $count = Produit::count();
            $reference = Produit::generateReference();
            $expectedNumber = mb_str_pad((string) ($count + 1), 6, '0', STR_PAD_LEFT);

            expect($reference)->toBe("PRD-{$expectedNumber}");
        });
    });

    describe('Relations', function () {
        it('appartient à une catégorie', function () {
            $category = Category::factory()->create();
            $this->produit->update(['category_id' => $category->id]);

            expect($this->produit->category)->toBeInstanceOf(Category::class)
                ->and($this->produit->category->id)->toBe($category->id);
        });

        it('appartient à un entrepôt', function () {
            $entrepot = Entrepot::factory()->create();
            $this->produit->update(['entrepot_id' => $entrepot->id]);

            expect($this->produit->entrepot)->toBeInstanceOf(Entrepot::class)
                ->and($this->produit->entrepot->id)->toBe($entrepot->id);
        });
    });

    describe('Scopes', function () {
        it('filtre les produits disponibles à l\'achat', function () {
            Produit::factory()->create(['achat' => false]);

            $produitsAchat = Produit::disponibleAchat()->get();

            expect($produitsAchat)->toHaveCount(1)
                ->and($produitsAchat->first()->achat)->toBeTrue();
        });

        it('filtre les produits disponibles à la vente', function () {
            Produit::factory()->create(['vente' => false]);

            $produitsVente = Produit::disponibleVente()->get();

            expect($produitsVente)->toHaveCount(1)
                ->and($produitsVente->first()->vente)->toBeTrue();
        });
    });

    describe('Méthodes métier', function () {
        it('vérifie la disponibilité à l\'achat', function () {
            expect($this->produit->isDisponibleAchat())->toBeTrue();

            $this->produit->update(['achat' => false]);
            expect($this->produit->isDisponibleAchat())->toBeFalse();
        });

        it('vérifie la disponibilité à la vente', function () {
            expect($this->produit->isDisponibleVente())->toBeTrue();

            $this->produit->update(['vente' => false]);
            expect($this->produit->isDisponibleVente())->toBeFalse();
        });

        it('calcule le volume correctement', function () {
            $volumeAttendu = 10.0 * 5.0 * 2.0; // 100

            expect($this->produit->volume)->toBe($volumeAttendu);
        });
    });

    describe('Attributs formatés', function () {
        it('formate le poids avec l\'unité', function () {
            expect($this->produit->poids_formate)->toBe('2.5 kg');
        });

        it('formate les dimensions', function () {
            expect($this->produit->dimensions_formatees)->toBe('10 x 5 x 2 cm');
        });

        it('retourne "Non spécifié" pour des dimensions nulles', function () {
            $this->produit->update([
                'longueur' => 0.0,
                'largeur' => 0.0,
                'hauteur' => 0.0,
            ]);

            expect($this->produit->dimensions_formatees)->toBe('Non spécifié');
        });
    });
});
