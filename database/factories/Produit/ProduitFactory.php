<?php

namespace Database\Factories\Produit;

use App\Models\Produit\Category;
use App\Models\Produit\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\Produit>
 */
class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['produit', 'service']);

        return [
            'type' => $type,
            'reference' => $this->generateReference($type),
            'name' => $this->generateProductName($type),
            'achat' => $this->faker->boolean(90), // 90% disponibles à l'achat
            'vente' => $this->faker->boolean(95), // 95% disponibles à la vente
            'description' => $this->faker->optional(0.8)->paragraph(2),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Génère une référence unique
     */
    private function generateReference(string $type): string
    {
        $prefix = $type === 'service' ? 'SRV' : 'PRD';
        $number = str_pad($this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);

        return $prefix . '-' . $number;
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
     * Produit (pas service)
     */
    public function produit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'produit',
            'reference' => $this->generateReference('produit'),
            'name' => $this->generateProductName('produit'),
        ]);
    }

    /**
     * Service
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'service',
            'reference' => $this->generateReference('service'),
            'name' => $this->generateProductName('service'),
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
     * Pour une catégorie spécifique
     */
    public function pourCategorie(int $categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }
}
