<?php

namespace Database\Factories\Produit;

use App\Enums\Produits\TypeMouvementStock;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\ProduitStockMvm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\ProduitStockMvm>
 */
class ProduitStockMvmFactory extends Factory
{
    protected $model = ProduitStockMvm::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(TypeMouvementStock::cases());

        return [
            'reference' => $this->generateReference(),
            'libelle' => $this->generateLibelle($type),
            'quantite' => $this->faker->numberBetween(1, 100),
            'type' => $type,
            'produit_stock_id' => ProduitStock::factory(),
        ];
    }

    /**
     * Mouvement d'entrée
     */
    public function entree(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TypeMouvementStock::ENTREE,
            'libelle' => $this->generateLibelleEntree(),
        ]);
    }

    /**
     * Mouvement de sortie
     */
    public function sortie(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TypeMouvementStock::SORTIE,
            'libelle' => $this->generateLibelleSortie(),
        ]);
    }

    /**
     * Mouvement pour un stock spécifique
     */
    public function pourStock(ProduitStock $stock): static
    {
        return $this->state(fn (array $attributes) => [
            'produit_stock_id' => $stock->id,
        ]);
    }

    /**
     * Mouvement avec quantité importante
     */
    public function quantiteImportante(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Mouvement avec petite quantité
     */
    public function petiteQuantite(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Mouvement récent
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Mouvement ancien
     */
    public function ancien(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }

    /**
     * Générer une référence unique
     */
    private function generateReference(): string
    {
        return 'MVM-' . strtoupper($this->faker->unique()->bothify('??###??'));
    }

    /**
     * Générer un libellé selon le type
     */
    private function generateLibelle(TypeMouvementStock $type): string
    {
        return $type === TypeMouvementStock::ENTREE
            ? $this->generateLibelleEntree()
            : $this->generateLibelleSortie();
    }

    /**
     * Générer un libellé d'entrée
     */
    private function generateLibelleEntree(): string
    {
        $libelles = [
            'Réception commande fournisseur',
            'Retour client',
            'Transfert depuis autre entrepôt',
            'Ajustement inventaire positif',
            'Livraison fournisseur',
            'Réapprovisionnement stock',
            'Correction stock',
            'Entrée manuelle',
            'Réception marchandise',
            'Stock initial',
        ];

        return $this->faker->randomElement($libelles);
    }

    /**
     * Générer un libellé de sortie
     */
    private function generateLibelleSortie(): string
    {
        $libelles = [
            'Vente client',
            'Consommation chantier',
            'Transfert vers autre entrepôt',
            'Ajustement inventaire négatif',
            'Perte/casse',
            'Échantillon gratuit',
            'Utilisation interne',
            'Sortie manuelle',
            'Livraison client',
            'Consommation production',
        ];

        return $this->faker->randomElement($libelles);
    }
}
