<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\Produit;
use App\Models\Produit\TarifFournisseur;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Seeder;

final class TarifFournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Création des tarifs fournisseurs...');

        if (TarifFournisseur::count() > 0) {
            $this->command->warn('⚠️  Les tarifs fournisseurs existent déjà, passage...');
            return;
        }

        // Vérifier que les dépendances existent
        if (Produit::count() === 0) {
            $this->command->error('❌ Aucun produit trouvé. Veuillez d\'abord exécuter ProduitSeeder.');
            return;
        }

        if (Tiers::where('nature', 'fournisseur')->count() === 0) {
            $this->command->error('❌ Aucun fournisseur trouvé. Veuillez d\'abord créer des tiers fournisseurs.');
            return;
        }

        $produits = Produit::all();
        $fournisseurs = Tiers::where('nature', 'fournisseur')->get();

        // Créer des tarifs pour chaque produit
        foreach ($produits as $produit) {
            $this->createTarifsForProduit($produit, $fournisseurs);
        }

        $this->command->info('✓ Tarifs fournisseurs créés avec succès');
    }

    /**
     * Créer des tarifs pour un produit donné
     */
    private function createTarifsForProduit(Produit $produit, $fournisseurs): void
    {
        // Nombre de fournisseurs pour ce produit (1 à 3)
        $nombreFournisseurs = random_int(1, min(3, $fournisseurs->count()));
        $fournisseursSelectionnes = $fournisseurs->random($nombreFournisseurs);

        foreach ($fournisseursSelectionnes as $index => $fournisseur) {
            $estPrefere = $index === 0; // Le premier fournisseur est préféré
            $estService = $produit->type->isService();

            $tarifData = [
                'produit_id' => $produit->id,
                'tiers_id' => $fournisseur->id,
                'reference_fournisseur' => $this->generateReferenceFournisseur($produit, $fournisseur),
                'prefere' => $estPrefere,
                'priorite' => $index + 1,
            ];

            // Créer le tarif selon le type de produit
            if ($estService) {
                TarifFournisseur::factory()
                    ->service()
                    ->valide()
                    ->create($tarifData);
            } else {
                $factory = TarifFournisseur::factory()
                    ->materiel()
                    ->valide();

                // Ajouter des remises pour certains produits
                if (random_int(1, 100) <= 60) {
                    $factory = $factory->avecRemises();
                }

                $factory->create($tarifData);
            }
        }
    }

    /**
     * Générer une référence fournisseur
     */
    private function generateReferenceFournisseur(Produit $produit, Tiers $fournisseur): string
    {
        // Obtenir le nom du fournisseur avec une valeur par défaut
        $nomFournisseur = $fournisseur->nom_entreprise ?? $fournisseur->nom ?? 'FOURNISSEUR';
        $prefixeFournisseur = strtoupper(substr($nomFournisseur, 0, 3));
        $suffixeProduit = substr($produit->reference, -4);

        return $prefixeFournisseur . '-' . $suffixeProduit . '-' . random_int(100, 999);
    }
}
