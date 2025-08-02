<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Enums\Produits\UniteMesure;
use App\Enums\Produits\UnitePoids;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\Produit>
 */
final class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    private static int $referenceCounter = 0;
    private static ?int $defaultCategoryId = null;
    private static ?int $defaultEntrepotId = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPhysicalProduct = true; // Par défaut, on considère que c'est un produit physique

        return [
            'reference' => $this->generateUniqueReference('produit'),
            'name' => $this->generateProductName('produit'),
            'achat' => $this->faker->boolean(90), // 90% disponibles à l'achat
            'vente' => $this->faker->boolean(95), // 95% disponibles à la vente
            'description' => $this->faker->optional(0.8)->paragraph(2),
            'serial_number' => $isPhysicalProduct ? $this->faker->optional(0.3)->bothify('SN-####-????') : null,
            'limit_stock' => $isPhysicalProduct ? $this->faker->randomFloat(2, 5, 50) : 0,
            'optimal_stock' => $isPhysicalProduct ? $this->faker->randomFloat(2, 50, 200) : 0,
            'poids_value' => $isPhysicalProduct ? $this->faker->randomFloat(2, 0.1, 100) : 0,
            'poids_unite' => $isPhysicalProduct ? $this->faker->randomElement(UnitePoids::values()) : UnitePoids::KILOGRAMME->value,
            'longueur' => $isPhysicalProduct ? $this->faker->randomFloat(2, 10, 5000) : 0,
            'largeur' => $isPhysicalProduct ? $this->faker->randomFloat(2, 10, 3000) : 0,
            'hauteur' => $isPhysicalProduct ? $this->faker->randomFloat(2, 5, 2000) : 0,
            'llh_unite' => $isPhysicalProduct ? $this->faker->randomElement(UniteMesure::values()) : UniteMesure::MILLIMETRE->value,
            'category_id' => Category::factory(),
            'entrepot_id' => Entrepot::factory(),
        ];
    }

    /**
     * Produit (pas service)
     */
    public function produit(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference' => $this->generateUniqueReference('produit'),
            'name' => $this->generateProductName('produit'),
        ]);
    }

    /**
     * Service
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference' => $this->generateUniqueReference('service'),
            'name' => $this->generateProductName('service'),
            // Pour les services, pas de dimensions ni poids
            'serial_number' => null,
            'limit_stock' => 0,
            'optimal_stock' => 0,
            'poids_value' => 0,
            'longueur' => 0,
            'largeur' => 0,
            'hauteur' => 0,
        ]);
    }

    /**
     * Disponible à l'achat
     */
    public function disponibleAchat(): static
    {
        return $this->state(fn (array $attributes) => [
            'achat' => true,
        ]);
    }

    /**
     * Non disponible à l'achat
     */
    public function nonDisponibleAchat(): static
    {
        return $this->state(fn (array $attributes) => [
            'achat' => false,
        ]);
    }

    /**
     * Disponible à la vente
     */
    public function disponibleVente(): static
    {
        return $this->state(fn (array $attributes) => [
            'vente' => true,
        ]);
    }

    /**
     * Non disponible à la vente
     */
    public function nonDisponibleVente(): static
    {
        return $this->state(fn (array $attributes) => [
            'vente' => false,
        ]);
    }

    /**
     * Avec description complète
     */
    public function avecDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $this->faker->paragraphs(3, true),
        ]);
    }

    /**
     * Produit avec dimensions spécifiques
     */
    public function avecDimensions(?float $longueur = null, ?float $largeur = null, ?float $hauteur = null, ?UniteMesure $unite = null): static
    {
        return $this->state(fn (array $attributes) => [
            'longueur' => $longueur ?? $this->faker->randomFloat(2, 100, 3000),
            'largeur' => $largeur ?? $this->faker->randomFloat(2, 100, 2000),
            'hauteur' => $hauteur ?? $this->faker->randomFloat(2, 50, 1000),
            'llh_unite' => ($unite ?? UniteMesure::MILLIMETRE)->value,
        ]);
    }

    /**
     * Produit avec poids spécifique
     */
    public function avecPoids(?float $poids = null, ?UnitePoids $unite = null): static
    {
        return $this->state(fn (array $attributes) => [
            'poids_value' => $poids ?? $this->faker->randomFloat(2, 0.5, 50),
            'poids_unite' => ($unite ?? UnitePoids::KILOGRAMME)->value,
        ]);
    }

    /**
     * Produit avec gestion de stock
     */
    public function avecStock(?float $limitStock = null, ?float $optimalStock = null): static
    {
        $limit = $limitStock ?? $this->faker->randomFloat(2, 5, 30);
        $optimal = $optimalStock ?? $this->faker->randomFloat(2, $limit + 10, $limit + 100);

        return $this->state(fn (array $attributes) => [
            'limit_stock' => $limit,
            'optimal_stock' => $optimal,
        ]);
    }

    /**
     * Produit pour une catégorie spécifique
     */
    public function pourCategorie(int $categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }

    /**
     * Produit pour un entrepôt spécifique
     */
    public function pourEntrepot(int $entrepotId): static
    {
        return $this->state(fn (array $attributes) => [
            'entrepot_id' => $entrepotId,
        ]);
    }

    /**
     * Produit de construction (matériaux lourds)
     */
    public function materiauConstruction(): static
    {
        return $this->state(fn (array $attributes) => [
            'poids_value' => $this->faker->randomFloat(2, 10, 500),
            'poids_unite' => UnitePoids::KILOGRAMME->value,
            'longueur' => $this->faker->randomFloat(2, 500, 6000),
            'largeur' => $this->faker->randomFloat(2, 200, 2500),
            'hauteur' => $this->faker->randomFloat(2, 50, 500),
            'llh_unite' => UniteMesure::MILLIMETRE->value,
        ]);
    }

    /**
     * Outillage léger
     */
    public function outillage(): static
    {
        return $this->state(fn (array $attributes) => [
            'poids_value' => $this->faker->randomFloat(2, 0.1, 10),
            'poids_unite' => UnitePoids::KILOGRAMME->value,
            'longueur' => $this->faker->randomFloat(2, 50, 800),
            'largeur' => $this->faker->randomFloat(2, 30, 300),
            'hauteur' => $this->faker->randomFloat(2, 20, 200),
            'llh_unite' => UniteMesure::MILLIMETRE->value,
        ]);
    }

    /**
     * Génère une référence unique en vérifiant la base de données
     */
    private function generateUniqueReference(string $type): string
    {
        $prefix = $type === 'service' ? 'SRV' : 'PRD';

        do {
            $number = mb_str_pad((string) $this->faker->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);
            $reference = $prefix.'-'.$number;
        } while (Produit::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Génère un nom de produit réaliste selon le type
     */
    private function generateProductName(string $type): string
    {
        if ($type === 'service') {
            return $this->faker->randomElement([
                'Installation plomberie',
                'Pose carrelage',
                'Peinture intérieure',
                'Montage cloisons',
                'Installation électrique',
                'Pose parquet',
                'Isolation combles',
                'Maçonnerie générale',
                'Couverture toiture',
                'Terrassement',
                'Démolition',
                'Nettoyage chantier',
                'Transport matériaux',
                'Location échafaudage',
                'Expertise technique',
                'Formation sécurité',
                'Maintenance équipements',
                'Conseil technique',
                'Étude de faisabilité',
                'Suivi de chantier',
            ]);
        }

        return $this->faker->randomElement([
            // Gros œuvre
            'Ciment Portland CEM II 32,5',
            'Sable 0/4 lavé',
            'Graviers 6/20',
            'Parpaing 20x20x50',
            'Brique rouge 5x10x22',
            'Poutrelle béton précontraint',
            'Fer à béton HA 10',
            'Mortier colle carrelage',

            // Plomberie
            'Tube PVC évacuation Ø100',
            'Raccord PVC coude 90°',
            'Robinet mitigeur lavabo',
            'Siphon lavabo chromé',
            'Radiateur acier 600x1200',
            'Chaudière gaz condensation',

            // Électricité
            'Câble électrique 3G2,5',
            'Prise 2P+T Legrand',
            'Interrupteur va-et-vient',
            'Tableau électrique 3 rangées',
            'Disjoncteur 20A',
            'Spot LED encastrable',

            // Menuiserie
            'Porte intérieure 83x204',
            'Fenêtre PVC 120x100',
            'Volet roulant électrique',
            'Parquet chêne massif 14mm',
            'Lambris sapin du Nord',

            // Carrelage
            'Carrelage grès cérame 60x60',
            'Faïence salle de bain 25x40',
            'Joint carrelage gris',
            'Colle carrelage C2',
            'Baguette finition alu',

            // Peinture
            'Peinture acrylique blanc mat',
            'Lasure bois chêne moyen',
            'Enduit de lissage',
            'Primaire d\'accrochage',
            'Rouleau laqueur 180mm',

            // Outillage
            'Perceuse visseuse 18V',
            'Scie circulaire 190mm',
            'Niveau laser rotatif',
            'Marteau perforateur SDS+',
            'Meuleuse 125mm',

            // Sécurité
            'Casque de chantier blanc',
            'Gants manutention',
            'Chaussures sécurité S3',
            'Harnais antichute',
            'Lunettes protection',

            // Toiture
            'Tuile béton rouge',
            'Ardoise naturelle 32x22',
            'Gouttière PVC 125mm',
            'Laine de verre 200mm',
            'Écran sous-toiture',

            // Quincaillerie
            'Vis agglo 4x50 (boîte 200)',
            'Cheville nylon 8x40',
            'Boulon tête hexagonale M12',
            'Serre-joint 120cm',
            'Cadenas haute sécurité',
        ]);
    }

    /**
     * Réinitialise les compteurs (utile pour les tests)
     */
    public static function resetCounters(): void
    {
        self::$referenceCounter = 0;
        self::$defaultCategoryId = null;
        self::$defaultEntrepotId = null;
    }

    /**
     * Génère une référence séquentielle (plus rapide que la vérification d'unicité)
     */
    private function generateSequentialReference(string $type): string
    {
        $prefix = $type === 'service' ? 'SRV' : 'PRD';
        self::$referenceCounter++;

        $number = mb_str_pad((string) self::$referenceCounter, 6, '0', STR_PAD_LEFT);

        return $prefix.'-'.$number;
    }

    /**
     * Obtient ou crée une catégorie par défaut pour les tests de performance
     */
    private function getOrCreateDefaultCategory(): int
    {
        if (self::$defaultCategoryId === null) {
            $category = Category::factory()->create(['name' => 'Catégorie Test Performance']);
            self::$defaultCategoryId = $category->id;
        }

        return self::$defaultCategoryId;
    }

    /**
     * Obtient ou crée un entrepôt par défaut pour les tests de performance
     */
    private function getOrCreateDefaultEntrepot(): int
    {
        if (self::$defaultEntrepotId === null) {
            $entrepot = Entrepot::factory()->create(['name' => 'Entrepôt Test Performance']);
            self::$defaultEntrepotId = $entrepot->id;
        }

        return self::$defaultEntrepotId;
    }

    /**
     * Mode performance pour les tests de performance (utilise des optimisations)
     */
    public function performance(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference' => $this->generateSequentialReference('produit'),
            'category_id' => $this->getOrCreateDefaultCategory(),
            'entrepot_id' => $this->getOrCreateDefaultEntrepot(),
        ]);
    }
}
