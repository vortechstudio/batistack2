<?php

declare(strict_types=1);

use App\Models\Produit\Category;
use App\Models\Produit\Service;

beforeEach(function () {
    $this->service = Service::factory()->create([
        'name' => 'Service Test',
        'reference' => 'SRV-000001',
        'description' => 'Description du service',
    ]);
});

describe('Service Model', function () {
    it('peut être créé avec les attributs requis', function () {
        expect($this->service)->toBeInstanceOf(Service::class)
            ->and($this->service->name)->toBe('Service Test')
            ->and($this->service->reference)->toBe('SRV-000001');
    });

    describe('generateReference()', function () {
        it('génère une référence unique', function () {
            $reference = Service::generateReference();

            expect($reference)->toStartWith('SRV-')
                ->and(strlen($reference))->toBe(10); // SRV- + 6 chiffres
        });

        it('génère des références séquentielles', function () {
            $count = Service::count();
            $reference = Service::generateReference();
            $expectedNumber = str_pad((string)($count + 1), 6, '0', STR_PAD_LEFT);

            expect($reference)->toBe("SRV-{$expectedNumber}");
        });
    });

    describe('Relations', function () {
        it('appartient à une catégorie', function () {
            $category = Category::factory()->create();
            $this->service->update(['category_id' => $category->id]);

            expect($this->service->category)->toBeInstanceOf(Category::class)
                ->and($this->service->category->id)->toBe($category->id);
        });
    });

    describe('Scopes', function () {
        it('filtre les services par catégorie', function () {
            $category = Category::factory()->create();
            $this->service->update(['category_id' => $category->id]);
            Service::factory()->create(); // Service sans catégorie

            $services = Service::parCategorie($category->id)->get();

            expect($services)->toHaveCount(1)
                ->and($services->first()->id)->toBe($this->service->id);
        });
    });
});
