<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\CategoryProduit;
use App\Models\Produit\Produit;
use App\Models\Produit\UniteMesure;
use Illuminate\Database\Seeder;

final class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏗️  Création des produits...');

        if (Produit::count() > 0) {
            $this->command->warn('⚠️  Les produits existent déjà, passage...');
            return;
        }

        // Vérifier que les dépendances existent
        if (CategoryProduit::count() === 0) {
            $this->command->error('❌ Aucune catégorie de produit trouvée. Veuillez d\'abord exécuter CategoryProduitSeeder.');
            return;
        }

        if (UniteMesure::count() === 0) {
            $this->command->error('❌ Aucune unité de mesure trouvée. Veuillez d\'abord exécuter UniteMesureSeeder.');
            return;
        }

        // Créer des produits pour chaque catégorie
        $this->createProduitsMateriaux();
        $this->createProduitsOutillage();
        $this->createProduitsServices();
        $this->createProduitsLocation();

        $this->command->info('✓ Produits créés avec succès');
    }

    /**
     * Créer des produits matériaux
     */
    private function createProduitsMateriaux(): void
    {
        $categoriesMateriaux = CategoryProduit::where('code', 'LIKE', 'MAT-%')->get();

        foreach ($categoriesMateriaux as $categorie) {
            $produits = $this->getProduitsParCategorie($categorie->code);

            foreach ($produits as $produitData) {
                Produit::factory()->materiau()->create([
                    'category_produit_id' => $categorie->id,
                    'unite_mesure_id' => UniteMesure::where('code', $produitData['unite'])->first()?->id,
                    'reference' => $produitData['reference'],
                    'nom' => $produitData['nom'],
                    'description' => $produitData['description'],
                    'poids' => $produitData['poids'] ?? null,
                    'dimensions' => $produitData['dimensions'] ?? null,
                    'duree_vie' => $produitData['duree_vie'] ?? null,
                    'garantie' => $produitData['garantie'] ?? null,
                ]);
            }
        }
    }

    /**
     * Créer des produits outillage
     */
    private function createProduitsOutillage(): void
    {
        $categoriesOutillage = CategoryProduit::where('code', 'LIKE', 'OUT-%')->get();

        foreach ($categoriesOutillage as $categorie) {
            $produits = $this->getProduitsParCategorie($categorie->code);

            foreach ($produits as $produitData) {
                Produit::factory()->materiau()->create([
                    'category_produit_id' => $categorie->id,
                    'unite_mesure_id' => UniteMesure::where('code', $produitData['unite'])->first()?->id,
                    'reference' => $produitData['reference'],
                    'nom' => $produitData['nom'],
                    'description' => $produitData['description'],
                    'poids' => $produitData['poids'] ?? null,
                    'garantie' => $produitData['garantie'] ?? null,
                ]);
            }
        }
    }

    /**
     * Créer des produits services
     */
    private function createProduitsServices(): void
    {
        $categoriesServices = CategoryProduit::where('code', 'LIKE', 'SER-%')->get();

        foreach ($categoriesServices as $categorie) {
            $produits = $this->getProduitsParCategorie($categorie->code);

            foreach ($produits as $produitData) {
                Produit::factory()->service()->create([
                    'category_produit_id' => $categorie->id,
                    'unite_mesure_id' => UniteMesure::where('code', $produitData['unite'])->first()?->id,
                    'reference' => $produitData['reference'],
                    'nom' => $produitData['nom'],
                    'description' => $produitData['description'],
                    'duree_standard' => $produitData['duree_standard'] ?? null,
                    'competence_requise' => $produitData['competence_requise'] ?? null,
                    'delai_intervention' => $produitData['delai_intervention'] ?? null,
                ]);
            }
        }
    }

    /**
     * Créer des produits location
     */
    private function createProduitsLocation(): void
    {
        $categoriesLocation = CategoryProduit::where('code', 'LIKE', 'LOC-%')->get();

        foreach ($categoriesLocation as $categorie) {
            $produits = $this->getProduitsParCategorie($categorie->code);

            foreach ($produits as $produitData) {
                Produit::factory()->service()->create([
                    'category_produit_id' => $categorie->id,
                    'unite_mesure_id' => UniteMesure::where('code', $produitData['unite'])->first()?->id,
                    'reference' => $produitData['reference'],
                    'nom' => $produitData['nom'],
                    'description' => $produitData['description'],
                    'duree_standard' => $produitData['duree_standard'] ?? null,
                ]);
            }
        }
    }

    /**
     * Obtenir les produits selon la catégorie
     */
    private function getProduitsParCategorie(string $codeCategorie): array
    {
        return match ($codeCategorie) {
            'MAT-GRO-BET' => [
                ['reference' => 'BET-001', 'nom' => 'Béton C25/30', 'description' => 'Béton prêt à l\'emploi C25/30', 'unite' => 'M3', 'poids' => 2400, 'duree_vie' => 50],
                ['reference' => 'BET-002', 'nom' => 'Béton C30/37', 'description' => 'Béton haute résistance C30/37', 'unite' => 'M3', 'poids' => 2450, 'duree_vie' => 50],
                ['reference' => 'BET-003', 'nom' => 'Béton fibré', 'description' => 'Béton avec fibres métalliques', 'unite' => 'M3', 'poids' => 2500, 'duree_vie' => 60],
            ],
            'MAT-GRO-CIM' => [
                ['reference' => 'CIM-001', 'nom' => 'Ciment Portland CEM I 52.5', 'description' => 'Ciment Portland haute résistance', 'unite' => 'SAC', 'poids' => 35, 'duree_vie' => 3],
                ['reference' => 'CIM-002', 'nom' => 'Ciment CEM II/A-LL 42.5', 'description' => 'Ciment au calcaire', 'unite' => 'SAC', 'poids' => 35, 'duree_vie' => 3],
                ['reference' => 'MOR-001', 'nom' => 'Mortier colle C2TE', 'description' => 'Mortier colle carrelage extérieur', 'unite' => 'SAC', 'poids' => 25, 'duree_vie' => 12],
            ],
            'MAT-GRO-AGR' => [
                ['reference' => 'SAB-001', 'nom' => 'Sable 0/4', 'description' => 'Sable fin pour béton', 'unite' => 'T', 'poids' => 1600],
                ['reference' => 'GRA-001', 'nom' => 'Gravier 4/20', 'description' => 'Gravier concassé 4/20', 'unite' => 'T', 'poids' => 1500],
                ['reference' => 'GRA-002', 'nom' => 'Tout-venant 0/31.5', 'description' => 'Tout-venant pour remblai', 'unite' => 'T', 'poids' => 1800],
            ],
            'MAT-SEC-PLA' => [
                ['reference' => 'PLA-001', 'nom' => 'Plaque BA13', 'description' => 'Plaque de plâtre standard 13mm', 'unite' => 'M2', 'poids' => 8.5, 'dimensions' => '1200x2600x13'],
                ['reference' => 'PLA-002', 'nom' => 'Plaque BA18', 'description' => 'Plaque de plâtre renforcée 18mm', 'unite' => 'M2', 'poids' => 12, 'dimensions' => '1200x2600x18'],
                ['reference' => 'RAI-001', 'nom' => 'Rail 48mm', 'description' => 'Rail métallique pour cloisons', 'unite' => 'ML', 'poids' => 0.6],
            ],
            'OUT-ELE' => [
                ['reference' => 'PER-001', 'nom' => 'Perceuse visseuse 18V', 'description' => 'Perceuse visseuse sans fil 18V', 'unite' => 'U', 'poids' => 1.8, 'garantie' => 24],
                ['reference' => 'SCI-001', 'nom' => 'Scie circulaire 190mm', 'description' => 'Scie circulaire portative 190mm', 'unite' => 'U', 'poids' => 4.2, 'garantie' => 24],
                ['reference' => 'MEU-001', 'nom' => 'Meuleuse 125mm', 'description' => 'Meuleuse d\'angle 125mm 1400W', 'unite' => 'U', 'poids' => 2.1, 'garantie' => 24],
            ],
            'OUT-MAN' => [
                ['reference' => 'MAR-001', 'nom' => 'Marteau rivoir 500g', 'description' => 'Marteau de charpentier 500g', 'unite' => 'U', 'poids' => 0.5, 'garantie' => 60],
                ['reference' => 'TOU-001', 'nom' => 'Tournevis cruciforme PH2', 'description' => 'Tournevis cruciforme PH2', 'unite' => 'U', 'poids' => 0.15, 'garantie' => 24],
                ['reference' => 'CLE-001', 'nom' => 'Clé à molette 250mm', 'description' => 'Clé à molette réglable 250mm', 'unite' => 'U', 'poids' => 0.4, 'garantie' => 60],
            ],
            'SER-ETU' => [
                ['reference' => 'ETU-001', 'nom' => 'Étude de sol G2', 'description' => 'Étude géotechnique G2 AVP', 'unite' => 'U', 'duree_standard' => 40, 'competence_requise' => 'Ingénieur géotechnique'],
                ['reference' => 'ETU-002', 'nom' => 'Plan d\'exécution', 'description' => 'Réalisation de plans d\'exécution', 'unite' => 'H', 'duree_standard' => 1, 'competence_requise' => 'Dessinateur BTP'],
                ['reference' => 'ETU-003', 'nom' => 'Calcul de structure', 'description' => 'Calcul de structure béton armé', 'unite' => 'H', 'duree_standard' => 1, 'competence_requise' => 'Ingénieur structure'],
            ],
            'SER-REA' => [
                ['reference' => 'REA-001', 'nom' => 'Maçonnerie traditionnelle', 'description' => 'Travaux de maçonnerie traditionnelle', 'unite' => 'H', 'duree_standard' => 1, 'competence_requise' => 'Maçon qualifié'],
                ['reference' => 'REA-002', 'nom' => 'Pose de carrelage', 'description' => 'Pose de carrelage sol et mur', 'unite' => 'M2', 'duree_standard' => 0.5, 'competence_requise' => 'Carreleur'],
                ['reference' => 'REA-003', 'nom' => 'Peinture intérieure', 'description' => 'Peinture murs et plafonds', 'unite' => 'M2', 'duree_standard' => 0.25, 'competence_requise' => 'Peintre'],
            ],
            'LOC-ENG' => [
                ['reference' => 'LOC-001', 'nom' => 'Pelleteuse 20T', 'description' => 'Location pelleteuse 20 tonnes', 'unite' => 'J', 'duree_standard' => 8],
                ['reference' => 'LOC-002', 'nom' => 'Dumper 3T', 'description' => 'Location dumper 3 tonnes', 'unite' => 'J', 'duree_standard' => 8],
                ['reference' => 'LOC-003', 'nom' => 'Compacteur plaque', 'description' => 'Location compacteur à plaque', 'unite' => 'J', 'duree_standard' => 8],
            ],
            'LOC-ECH' => [
                ['reference' => 'ECH-001', 'nom' => 'Échafaudage façade', 'description' => 'Location échafaudage de façade', 'unite' => 'M2', 'duree_standard' => 720], // 30 jours
                ['reference' => 'ECH-002', 'nom' => 'Tour d\'échafaudage', 'description' => 'Location tour d\'échafaudage mobile', 'unite' => 'J', 'duree_standard' => 8],
                ['reference' => 'ECH-003', 'nom' => 'Échafaudage roulant', 'description' => 'Location échafaudage roulant', 'unite' => 'J', 'duree_standard' => 8],
            ],
            default => [],
        };
    }
}
