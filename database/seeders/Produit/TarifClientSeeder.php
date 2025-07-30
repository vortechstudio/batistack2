<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\Produit;
use App\Models\Produit\TarifClient;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Seeder;

final class TarifClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💵 Création des tarifs clients...');

        if (TarifClient::count() > 0) {
            $this->command->warn('⚠️  Les tarifs clients existent déjà, passage...');
            return;
        }

        // Vérifier que les dépendances existent
        if (Produit::count() === 0) {
            $this->command->error('❌ Aucun produit trouvé. Veuillez d\'abord exécuter ProduitSeeder.');
            return;
        }

        $produits = Produit::all();
        $clients = Tiers::where('nature', 'client')->get();

        // Créer des tarifs généraux pour tous les produits
        $this->createTarifsGeneraux($produits);

        // Créer des tarifs spécifiques pour certains clients
        if ($clients->count() > 0) {
            $this->createTarifsSpecifiques($produits, $clients);
        }

        $this->command->info('✓ Tarifs clients créés avec succès');
    }

    /**
     * Créer des tarifs généraux (sans client spécifique)
     */
    private function createTarifsGeneraux($produits): void
    {
        foreach ($produits as $produit) {
            $estService = $produit->type->isService();

            // Tarif général particulier
            $this->createTarifGeneral($produit, 'particulier', $estService);

            // Tarif général professionnel
            $this->createTarifGeneral($produit, 'professionnel', $estService);

            // Tarif général entreprise
            $this->createTarifGeneral($produit, 'entreprise', $estService);
        }
    }

    /**
     * Créer un tarif général pour un type de client
     */
    private function createTarifGeneral(Produit $produit, string $typeClient, bool $estService): void
    {
        $tarifData = [
            'produit_id' => $produit->id,
            'tiers_id' => null, // Tarif général
            'type_client' => $typeClient,
        ];

        $factory = TarifClient::factory()->general()->valide();

        if ($estService) {
            $factory = $factory->service();
        } else {
            $factory = $factory->materiel();
            
            // Ajouter des tarifs dégressifs pour certains produits
            if (random_int(1, 100) <= 40) {
                $factory = $factory->avecDegressif();
            }
        }

        // Appliquer le type de client
        $factory = match ($typeClient) {
            'particulier' => $factory->particulier(),
            'professionnel' => $factory->professionnel(),
            'entreprise' => $factory->entreprise(),
            default => $factory,
        };

        $factory->create($tarifData);
    }

    /**
     * Créer des tarifs spécifiques pour certains clients
     */
    private function createTarifsSpecifiques($produits, $clients): void
    {
        // Sélectionner quelques clients pour des tarifs spéciaux
        $clientsSpeciaux = $clients->random(min(5, $clients->count()));

        foreach ($clientsSpeciaux as $client) {
            // Sélectionner quelques produits pour ce client
            $produitsClient = $produits->random(random_int(3, min(10, $produits->count())));

            foreach ($produitsClient as $produit) {
                $this->createTarifSpecifique($produit, $client);
            }
        }
    }

    /**
     * Créer un tarif spécifique pour un client
     */
    private function createTarifSpecifique(Produit $produit, Tiers $client): void
    {
        $estService = $produit->type->isService();
        $typeClient = $this->determinerTypeClient($client);

        $tarifData = [
            'produit_id' => $produit->id,
            'tiers_id' => $client->id,
            'type_client' => $typeClient,
            'priorite' => 1, // Priorité haute pour les tarifs spécifiques
        ];

        $factory = TarifClient::factory()->valide()->negociable();

        if ($estService) {
            $factory = $factory->service();
        } else {
            $factory = $factory->materiel();
            
            // Plus de chances d'avoir des tarifs dégressifs pour les clients spéciaux
            if (random_int(1, 100) <= 70) {
                $factory = $factory->avecDegressif();
            }
        }

        // Appliquer le type de client
        $factory = match ($typeClient) {
            'particulier' => $factory->particulier(),
            'professionnel' => $factory->professionnel(),
            'entreprise' => $factory->entreprise(),
            default => $factory,
        };

        $factory->create($tarifData);
    }

    /**
     * Déterminer le type de client selon ses caractéristiques
     */
    private function determinerTypeClient(Tiers $client): string
    {
        // Si le client a un nom d'entreprise, c'est probablement un professionnel ou une entreprise
        if ($client->nom_entreprise) {
            // Logique simple : si le nom contient certains mots-clés, c'est une entreprise
            $motsClesEntreprise = ['SA', 'SARL', 'SAS', 'EURL', 'SNC', 'Groupe', 'Holding'];
            
            foreach ($motsClesEntreprise as $motCle) {
                if (str_contains($client->nom_entreprise, $motCle)) {
                    return 'entreprise';
                }
            }
            
            return 'professionnel';
        }

        return 'particulier';
    }
}