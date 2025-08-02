<?php

declare(strict_types=1);

use App\Models\Produit\Produit;
use App\Models\Produit\Service;
use App\Models\Produit\TarifClient;

beforeEach(function () {
    $this->produit = Produit::factory()->create();
    $this->service = Service::factory()->create();
    $this->tarifProduit = TarifClient::factory()->create([
        'produit_id' => $this->produit->id,
        'service_id' => null,
        'prix_unitaire' => 100.00,
        'taux_tva' => 20.0,
    ]);
    $this->tarifService = TarifClient::factory()->create([
        'produit_id' => null,
        'service_id' => $this->service->id,
        'prix_unitaire' => 150.00,
        'taux_tva' => 20.0,
    ]);
});

describe('TarifClient Model', function () {
    it('peut être créé avec les attributs requis', function () {
        expect($this->tarifProduit)->toBeInstanceOf(TarifClient::class)
            ->and($this->tarifProduit->prix_unitaire)->toBe(100.00)
            ->and($this->tarifProduit->taux_tva)->toBe(20.0);
    });

    describe('Relations', function () {
        it('appartient à un produit', function () {
            expect($this->tarifProduit->produit)->toBeInstanceOf(Produit::class)
                ->and($this->tarifProduit->produit->id)->toBe($this->produit->id);
        });

        it('appartient à un service', function () {
            expect($this->tarifService->service)->toBeInstanceOf(Service::class)
                ->and($this->tarifService->service->id)->toBe($this->service->id);
        });
    });

    describe('Scopes', function () {
        it('filtre les tarifs de produits', function () {
            $tarifs = TarifClient::pourProduits()->get();

            expect($tarifs)->toHaveCount(1)
                ->and($tarifs->first()->produit_id)->not->toBeNull()
                ->and($tarifs->first()->service_id)->toBeNull();
        });

        it('filtre les tarifs de services', function () {
            $tarifs = TarifClient::pourServices()->get();

            expect($tarifs)->toHaveCount(1)
                ->and($tarifs->first()->service_id)->not->toBeNull()
                ->and($tarifs->first()->produit_id)->toBeNull();
        });

        it('filtre par gamme de prix HT', function () {
            $tarifs = TarifClient::prixHTEntre(90, 110)->get();

            expect($tarifs)->toHaveCount(1)
                ->and($tarifs->first()->prix_unitaire)->toBeBetween(90, 110);
        });

        it('filtre par taux de TVA', function () {
            TarifClient::factory()->create(['taux_tva' => 10.0]);

            $tarifs = TarifClient::avecTauxTVA(20.0)->get();

            expect($tarifs)->toHaveCount(2);
        });
    });

    describe('Calculs de prix', function () {
        it('calcule le prix TTC', function () {
            expect($this->tarifProduit->prix_ttc)->toBe(120.00); // 100 + 20%
        });

        it('calcule le montant de TVA', function () {
            expect($this->tarifProduit->montant_tva)->toBe(20.00); // 100 * 20%
        });

        it('calcule le prix total HT pour une quantité', function () {
            $total = $this->tarifProduit->calculerPrixTotalHT(5);
            expect($total)->toBe(500.00); // 100 * 5
        });

        it('calcule le prix total TTC pour une quantité', function () {
            $total = $this->tarifProduit->calculerPrixTotalTTC(5);
            expect($total)->toBe(600.00); // 500 + 20%
        });

        it('calcule le montant total de TVA pour une quantité', function () {
            $tva = $this->tarifProduit->calculerMontantTotalTVA(5);
            expect($tva)->toBe(100.00); // 500 * 20%
        });
    });

    describe('Vérifications de type', function () {
        it('identifie un tarif produit', function () {
            expect($this->tarifProduit->isProduit())->toBeTrue()
                ->and($this->tarifProduit->isService())->toBeFalse();
        });

        it('identifie un tarif service', function () {
            expect($this->tarifService->isService())->toBeTrue()
                ->and($this->tarifService->isProduit())->toBeFalse();
        });
    });

    describe('Attributs calculés', function () {
        it('retourne l\'élément tarifé', function () {
            expect($this->tarifProduit->element_tarife)->toBeInstanceOf(Produit::class);
            expect($this->tarifService->element_tarife)->toBeInstanceOf(Service::class);
        });

        it('retourne le nom de l\'élément', function () {
            expect($this->tarifProduit->nom_element)->toBe($this->produit->name);
            expect($this->tarifService->nom_element)->toBe($this->service->name);
        });
    });

    describe('Formatage', function () {
        it('formate le prix HT', function () {
            expect($this->tarifProduit->prix_ht_formate)->toBe('100,00 € HT');
        });

        it('formate le prix TTC', function () {
            expect($this->tarifProduit->prix_ttc_formate)->toBe('120,00 € TTC');
        });

        it('formate le taux de TVA', function () {
            expect($this->tarifProduit->taux_tva_formate)->toBe('20%');
        });
    });
});
