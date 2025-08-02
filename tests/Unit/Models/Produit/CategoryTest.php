<?php

declare(strict_types=1);

use App\Models\Produit\Category;

describe('Category Model', function () {
    it('peut être créé avec les attributs requis', function () {
        $category = Category::factory()->create([
            'name' => 'Catégorie Test',
        ]);

        expect($category)->toBeInstanceOf(Category::class)
            ->and($category->name)->toBe('Catégorie Test');
    });

    it('n\'a pas de timestamps', function () {
        $category = new Category();

        expect($category->timestamps)->toBeFalse();
    });

    describe('Relations', function () {
        it('peut avoir une catégorie parente', function () {
            $parent = Category::factory()->create(['name' => 'Catégorie Parent']);
            $child = Category::factory()->create([
                'name' => 'Catégorie Enfant',
                'category_id' => $parent->id,
            ]);

            expect($child->parent)->toBeInstanceOf(Category::class)
                ->and($child->parent->id)->toBe($parent->id)
                ->and($child->parent->name)->toBe('Catégorie Parent');
        });

        it('peut ne pas avoir de parent', function () {
            $category = Category::factory()->create(['category_id' => null]);

            expect($category->parent)->toBeNull();
        });
    });
});
