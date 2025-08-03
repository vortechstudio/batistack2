<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Models\Produit\Category;
use App\Models\Produit\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit\Service>
 */
final class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => $this->generateReference(),
            'name' => $this->generateServiceName(),
            'description' => $this->faker->optional(0.9)->paragraph(3),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Service de construction
     */
    public function construction(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Maçonnerie générale',
                'Gros œuvre béton armé',
                'Construction murs porteurs',
                'Fondations et soubassements',
                'Charpente traditionnelle',
                'Couverture tuiles',
            ]),
        ]);
    }

    /**
     * Service de rénovation
     */
    public function renovation(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Rénovation complète appartement',
                'Réhabilitation maison ancienne',
                'Mise aux normes électriques',
                'Isolation thermique',
                'Changement menuiseries',
                'Réfection toiture',
            ]),
        ]);
    }

    /**
     * Service de finition
     */
    public function finition(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Peinture décorative',
                'Pose carrelage haut de gamme',
                'Installation parquet massif',
                'Aménagement cuisine sur mesure',
                'Création salle de bain design',
                'Pose papier peint',
            ]),
        ]);
    }

    /**
     * Service pour une catégorie spécifique
     */
    public function pourCategorie(int $categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }

    /**
     * Service avec description détaillée
     */
    public function avecDescriptionComplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $this->faker->paragraphs(4, true)."\n\nPrestations incluses :\n".
                '- '.implode("\n- ", $this->faker->sentences(5)),
        ]);
    }

    /**
     * Génère une référence unique
     */
    private function generateReference(): string
    {
        $number = mb_str_pad((string) $this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);

        return 'SRV-'.$number;
    }

    /**
     * Génère un nom de service réaliste pour le BTP
     */
    private function generateServiceName(): string
    {
        return $this->faker->randomElement([
            // Services de construction
            'Installation plomberie complète',
            'Pose carrelage au m²',
            'Peinture intérieure et extérieure',
            'Montage cloisons placo',
            'Installation électrique résidentielle',
            'Pose parquet et revêtements sols',
            'Isolation thermique combles',
            'Maçonnerie générale',
            'Couverture et zinguerie',
            'Terrassement et VRD',
            'Démolition contrôlée',
            'Nettoyage fin de chantier',

            // Services spécialisés
            'Étanchéité toiture terrasse',
            'Installation chauffage central',
            'Pose menuiseries extérieures',
            'Ravalement de façade',
            'Aménagement salle de bain',
            'Installation cuisine équipée',
            'Création escalier sur mesure',
            'Pose velux et fenêtres de toit',

            // Services techniques
            'Diagnostic technique bâtiment',
            'Étude de faisabilité',
            'Maîtrise d\'œuvre',
            'Coordination sécurité',
            'Contrôle qualité travaux',
            'Expertise sinistre',
            'Formation sécurité chantier',
            'Maintenance préventive',

            // Services logistiques
            'Transport matériaux',
            'Location échafaudage',
            'Livraison sur chantier',
            'Évacuation gravats',
            'Gardiennage chantier',
            'Nettoyage quotidien',

            // Services de finition
            'Pose faïence et carrelage',
            'Application enduits décoratifs',
            'Installation domotique',
            'Pose stores et volets',
            'Aménagement espaces verts',
            'Installation clôtures',
        ]);
    }
}
