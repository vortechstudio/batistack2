<?php

namespace Database\Seeders\Produit;

use App\Models\Produit\Entrepot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrepotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des entrepôts principaux avec des données spécifiques
        $entrepotsPrincipaux = [
            [
                'name' => 'Entrepôt Central Paris',
                'description' => 'Entrepôt principal de la région parisienne. Centre de distribution majeur avec capacité de stockage de 15 000 m². Spécialisé dans tous types de matériaux de construction.',
                'address' => '125 Avenue de la République',
                'code_postal' => '75011',
                'ville' => 'Paris',
                'status' => true,
            ],
            [
                'name' => 'Plateforme Lyon Sud',
                'description' => 'Grande plateforme logistique desservant la région Rhône-Alpes. Stockage de 12 000 m² avec zones climatisées pour matériaux sensibles.',
                'address' => '45 Rue de l\'Industrie',
                'code_postal' => '69200',
                'ville' => 'Vénissieux',
                'status' => true,
            ],
            [
                'name' => 'Centre Logistique Marseille',
                'description' => 'Hub logistique pour le Sud-Est de la France. Entrepôt de 10 000 m² avec accès direct autoroute et zone de chargement poids lourds.',
                'address' => '78 Boulevard de la Méditerranée',
                'code_postal' => '13015',
                'ville' => 'Marseille',
                'status' => true,
            ],
            [
                'name' => 'Dépôt Lille Nord',
                'description' => 'Entrepôt régional pour le Nord-Pas-de-Calais. Capacité 8 000 m² avec zone de stockage extérieur pour matériaux lourds.',
                'address' => '23 Rue des Flandres',
                'code_postal' => '59000',
                'ville' => 'Lille',
                'status' => true,
            ],
            [
                'name' => 'Entrepôt Bordeaux Ouest',
                'description' => 'Centre de distribution pour la Nouvelle-Aquitaine. Entrepôt moderne de 9 000 m² avec système de gestion automatisé.',
                'address' => '156 Avenue de l\'Atlantique',
                'code_postal' => '33000',
                'ville' => 'Bordeaux',
                'status' => true,
            ],
        ];

        foreach ($entrepotsPrincipaux as $entrepot) {
            Entrepot::create($entrepot);
        }

        // Créer des entrepôts régionaux avec la factory
        Entrepot::factory()->count(8)->regional()->avecAdresseComplete()->create();

        // Créer des entrepôts spécialisés
        Entrepot::factory()->count(6)->specialise()->avecAdresseComplete()->create();

        // Créer quelques entrepôts supplémentaires avec des statuts variés
        Entrepot::factory()->count(5)->actif()->avecAdresseComplete()->create();
        Entrepot::factory()->count(2)->inactif()->avecAdresseComplete()->create();

        // Créer quelques entrepôts avec des données partielles (pour tester la robustesse)
        Entrepot::factory()->count(3)->create();

        $this->command->info('Entrepôts créés avec succès !');
        $this->command->info('Total entrepôts : ' . Entrepot::count());
        $this->command->info('Entrepôts actifs : ' . Entrepot::where('status', true)->count());
        $this->command->info('Entrepôts inactifs : ' . Entrepot::where('status', false)->count());
    }
}
