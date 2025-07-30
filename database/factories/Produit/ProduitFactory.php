<?php

declare(strict_types=1);

namespace Database\Factories\Produit;

use App\Enums\Produits\TypeProduit;
use App\Models\Produit\CategoryProduit;
use App\Models\Produit\Produit;
use App\Models\Produit\UniteMesure;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(TypeProduit::cases());
        $estService = $type->isService();

        return [
            'reference' => $this->generateReference($type),
            'nom' => $this->generateNom($type),
            'description' => $this->faker->optional(0.8)->paragraph(),
            'description_courte' => $this->faker->optional(0.6)->sentence(),
            'type' => $type,
            'category_produit_id' => CategoryProduit::factory(),
            'unite_mesure_id' => UniteMesure::factory(),
            'actif' => $this->faker->boolean(90),
            'achat' => $this->faker->boolean(80),
            'vente' => $this->faker->boolean(85),

            // Champs matériaux
            'poids' => $estService ? null : $this->faker->optional(0.7)->randomFloat(3, 0.1, 100),
            'code_barre' => $estService ? null : $this->faker->optional(0.3)->ean13(),
            'reference_fournisseur' => $estService ? null : $this->faker->optional(0.5)->bothify('REF-####-???'),
            'duree_vie' => $estService ? null : $this->faker->optional(0.4)->numberBetween(12, 120),
            'garantie' => $estService ? null : $this->faker->optional(0.3)->numberBetween(6, 60),

            // Champs services
            'duree_standard' => $estService ? $this->faker->randomFloat(2, 0.5, 8) : null,
            'competence_requise' => $estService ? $this->faker->optional(0.6)->randomElement(['Débutant', 'Intermédiaire', 'Expert', 'Spécialisé']) : null,
            'cout_deplacement' => $estService ? $this->faker->optional(0.7)->randomFloat(2, 10, 100) : null,
            'delai_intervention' => $estService ? $this->faker->optional(0.5)->numberBetween(1, 30) : null,
            'deplacement_inclus' => $estService ? $this->faker->boolean(30) : null,
            'urgence_possible' => $estService ? $this->faker->boolean(40) : null,
            'majoration_urgence' => $estService ? $this->faker->optional(0.3)->randomFloat(2, 10, 50) : null,

            // Champs JSON
            'dimensions' => $estService ? null : $this->faker->optional(0.5)->randomElements([
                'longueur' => $this->faker->randomFloat(2, 1, 500),
                'largeur' => $this->faker->randomFloat(2, 1, 500),
                'hauteur' => $this->faker->randomFloat(2, 1, 500),
                'diametre' => $this->faker->randomFloat(2, 1, 100),
            ]),
            'horaires_disponibilite' => $estService ? $this->faker->optional(0.4)->randomElements([
                'lundi' => ['08:00', '18:00'],
                'mardi' => ['08:00', '18:00'],
                'mercredi' => ['08:00', '18:00'],
                'jeudi' => ['08:00', '18:00'],
                'vendredi' => ['08:00', '18:00'],
                'samedi' => ['09:00', '12:00'],
            ]) : null,
            'zones_intervention' => $estService ? $this->faker->optional(0.6)->randomElements([
                'departements' => $this->faker->randomElements(['75', '92', '93', '94'], 2),
                'rayon_km' => $this->faker->numberBetween(10, 100),
            ]) : null,
            'certifications' => $this->faker->optional(0.3)->randomElements([
                'ISO 9001',
                'Qualibat',
                'RGE',
                'Qualifelec',
            ], 2),
            'documents_joints' => $this->faker->optional(0.2)->randomElements([
                'fiche_technique.pdf',
                'notice_utilisation.pdf',
                'certificat_conformite.pdf',
            ]),
            'metadata' => $this->faker->optional(0.3)->randomElements([
                'tags' => $this->faker->words(3),
                'notes_internes' => $this->faker->sentence(),
                'fournisseur_prefere' => $this->faker->company(),
            ]),
        ];
    }

    /**
     * Produit de type matériau
     */
    public function materiau(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TypeProduit::MATERIAU,
            'duree_standard' => null,
            'competence_requise' => null,
            'cout_deplacement' => null,
            'delai_intervention' => null,
            'deplacement_inclus' => null,
            'urgence_possible' => null,
            'majoration_urgence' => null,
            'horaires_disponibilite' => null,
            'zones_intervention' => null,
            'poids' => $this->faker->randomFloat(3, 0.1, 50),
            'code_barre' => $this->faker->optional(0.5)->ean13(),
            'reference_fournisseur' => $this->faker->bothify('MAT-####-???'),
        ]);
    }

    /**
     * Produit de type service
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $this->faker->randomElement([
                TypeProduit::MAIN_OEUVRE,
                TypeProduit::LOCATION,
                TypeProduit::TRANSPORT,
                TypeProduit::ETUDE,
                TypeProduit::CONSEIL,
            ]),
            'poids' => null,
            'code_barre' => null,
            'reference_fournisseur' => null,
            'duree_vie' => null,
            'garantie' => null,
            'dimensions' => null,
            'duree_standard' => $this->faker->randomFloat(2, 0.5, 8),
            'competence_requise' => $this->faker->randomElement(['Débutant', 'Intermédiaire', 'Expert']),
            'cout_deplacement' => $this->faker->randomFloat(2, 20, 80),
        ]);
    }

    /**
     * Produit actif
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => true,
        ]);
    }

    /**
     * Produit vendable
     */
    public function vendable(): static
    {
        return $this->state(fn (array $attributes) => [
            'vente' => true,
            'actif' => true,
        ]);
    }

    /**
     * Produit achetable
     */
    public function achetable(): static
    {
        return $this->state(fn (array $attributes) => [
            'achat' => true,
            'actif' => true,
        ]);
    }

    private function generateReference(TypeProduit $type): string
    {
        $prefix = match ($type) {
            TypeProduit::MATERIAU => 'MAT',
            TypeProduit::OUTILLAGE => 'OUT',
            TypeProduit::MAIN_OEUVRE => 'MO',
            TypeProduit::LOCATION => 'LOC',
            TypeProduit::TRANSPORT => 'TRA',
            TypeProduit::ETUDE => 'ETU',
            TypeProduit::CONSEIL => 'CON',
            TypeProduit::FORFAIT => 'FOR',
            TypeProduit::CONSOMMABLE => 'CSM',
        };

        return $prefix . '-' . $this->faker->unique()->numerify('####');
    }

    private function generateNom(TypeProduit $type): string
    {
        $noms = match ($type) {
            TypeProduit::MATERIAU => [
                'Béton C25/30',
                'Parpaing 20x20x50',
                'Brique rouge',
                'Plaque de plâtre BA13',
                'Isolant laine de verre',
                'Carrelage grès cérame',
                'Peinture acrylique',
                'Enduit de façade',
            ],
            TypeProduit::OUTILLAGE => [
                'Perceuse visseuse',
                'Scie circulaire',
                'Niveau laser',
                'Échafaudage mobile',
                'Bétonnière 150L',
                'Compresseur',
                'Meuleuse 125mm',
                'Marteau perforateur',
            ],
            TypeProduit::MAIN_OEUVRE => [
                'Pose de carrelage',
                'Peinture intérieure',
                'Maçonnerie générale',
                'Plomberie sanitaire',
                'Électricité résidentielle',
                'Menuiserie bois',
                'Couverture tuiles',
                'Isolation thermique',
            ],
            TypeProduit::LOCATION => [
                'Location échafaudage',
                'Location nacelle',
                'Location benne',
                'Location compacteur',
                'Location mini-pelle',
                'Location groupe électrogène',
                'Location grue mobile',
                'Location camion plateau',
            ],
            TypeProduit::TRANSPORT => [
                'Livraison matériaux',
                'Transport engins',
                'Évacuation gravats',
                'Transport personnel',
                'Livraison urgente',
                'Transport exceptionnel',
                'Déménagement chantier',
                'Transport béton',
            ],
            TypeProduit::ETUDE => [
                'Étude de sol',
                'Diagnostic structure',
                'Étude thermique',
                'Relevé topographique',
                'Étude béton armé',
                'Diagnostic amiante',
                'Étude acoustique',
                'Expertise technique',
            ],
            TypeProduit::CONSEIL => [
                'Conseil technique',
                'Formation sécurité',
                'Audit qualité',
                'Assistance maîtrise d\'œuvre',
                'Conseil juridique',
                'Formation métier',
                'Conseil environnemental',
                'Expertise sinistre',
            ],
            TypeProduit::FORFAIT => [
                'Forfait gros œuvre',
                'Forfait second œuvre',
                'Forfait finitions',
                'Forfait rénovation',
                'Forfait extension',
                'Forfait aménagement',
                'Forfait maintenance',
                'Forfait dépannage',
            ],
            TypeProduit::CONSOMMABLE => [
                'Vis à bois',
                'Chevilles plastique',
                'Colle carrelage',
                'Joint silicone',
                'Papier abrasif',
                'Lame scie',
                'Foret béton',
                'Gants protection',
            ],
        };

        return $this->faker->randomElement($noms);
    }
}