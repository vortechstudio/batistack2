<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Helpers\Helpers;
use App\Models\Chantiers\ChantierAddress;
use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\ChantierIntervention;
use App\Models\Chantiers\Chantiers;
use App\Models\Chantiers\ChantierTask;
use App\Models\Commerce\Avoir;
use App\Models\Commerce\AvoirLigne;
use App\Models\Commerce\Commande;
use App\Models\Commerce\CommandeLigne;
use App\Models\Commerce\Devis;
use App\Models\Commerce\DevisLigne;
use App\Models\Commerce\Facture;
use App\Models\Commerce\FactureLigne;
use App\Models\Commerce\FacturePaiement;
use App\Models\Core\Company;
use App\Models\Core\ModeReglement;
use App\Models\Produit\Category;
use App\Models\Produit\Entrepot;
use App\Models\Produit\Produit;
use App\Models\Produit\ProduitStock;
use App\Models\Produit\ProduitStockMvm;
use App\Models\Produit\Service;
use App\Models\Produit\TarifClient;
use App\Models\Produit\TarifFournisseur;
use App\Models\RH\Employe;
use App\Models\RH\EmployeBank;
use App\Models\RH\EmployeContrat;
use App\Models\RH\EmployeInfo;
use App\Models\RH\NoteFrais;
use App\Models\RH\NoteFraisDetail;
use App\Models\RH\Paie\ProfilPaie;
use App\Models\Tiers\Tiers;
use App\Models\Tiers\TiersAddress;
use App\Models\Tiers\TiersBank;
use App\Models\Tiers\TiersClient;
use App\Models\Tiers\TiersContact;
use App\Models\Tiers\TiersFournisseur;
use App\Models\User;
use Database\Seeders\Paie\ProfilPaieSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Démarrage du seeding de la base de données...');

        // Initialisation des données de base
        $this->seedCoreData();

        // Seeding des données métier
        $this->seedBusinessData();

        // Seeding des données RH
        $this->seedRHData();

        $this->command->info('✅ Seeding terminé avec succès !');
    }

    /**
     * Seed des données de base (utilisateurs, entreprise, stockage)
     */
    private function seedCoreData(): void
    {
        $this->command->info('📋 Seeding des données de base...');

        $this->seedUsers();
        $this->seedCompany();
        $this->setupStorage();
    }

    /**
     * Seed des données métier (tiers, chantiers, commerce)
     */
    private function seedBusinessData(): void
    {
        $this->command->info('🏢 Seeding des données métier...');

        $this->seedTiers();
        $this->seedProduits();
        $this->seedChantiers();
        $this->seedCommerce();
    }

    /**
     * Seed des données RH
     */
    private function seedRHData(): void
    {
        $this->command->info('👥 Seeding des données RH...');

        $this->seedProfilsPaie();
        $this->seedEmployes();
        $this->seedNotesFrais();
    }

    /**
     * Création des utilisateurs de test
     */
    private function seedUsers(): void
    {
        if (User::count() > 0) {
            $this->command->warn('⚠️  Les utilisateurs existent déjà, passage...');

            return;
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '+33745521533',
        ]);

        $this->command->info('✓ Utilisateur de test créé');
    }

    /**
     * Création de l'entreprise
     */
    private function seedCompany(): void
    {
        if (Company::count() > 0) {
            $this->command->warn('⚠️  L\'entreprise existe déjà, passage...');

            return;
        }

        Company::create();
        $this->command->info('✓ Entreprise créée');
    }

    /**
     * Configuration du stockage
     */
    private function setupStorage(): void
    {
        if (! Storage::disk('public')->exists('company')) {
            Storage::disk('public')->makeDirectory('company');
            $this->command->info('✓ Répertoire company créé');
        }
    }

    /**
     * Création des tiers (clients/fournisseurs)
     */
    private function seedTiers(): void
    {
        if (Tiers::count() > 0) {
            $this->command->warn('⚠️  Les tiers existent déjà, passage...');

            return;
        }

        $tiersCount = random_int(5, 30);
        $tiers = Tiers::factory($tiersCount)->create();

        foreach ($tiers as $tier) {
            $this->createTiersRelations($tier);
        }

        $this->command->info("✓ {$tiersCount} tiers créés avec leurs relations");
    }

    /**
     * Création des relations d'un tiers
     */
    private function createTiersRelations(Tiers $tiers): void
    {
        // Adresses
        TiersAddress::factory(random_int(1, 2))->create([
            'tiers_id' => $tiers->id,
        ]);

        // Contacts
        TiersContact::factory(random_int(1, 10))->create([
            'tiers_id' => $tiers->id,
        ]);

        // Spécialisation selon la nature
        if ($tiers->nature->value === 'fournisseur') {
            TiersFournisseur::factory()->create([
                'tva' => $tiers->tva,
                'num_tva' => $tiers->num_tva,
                'tiers_id' => $tiers->id,
            ]);
        } else {
            TiersClient::factory()->create([
                'tva' => $tiers->tva,
                'num_tva' => $tiers->num_tva,
                'tiers_id' => $tiers->id,
            ]);
        }

        // Informations bancaires
        TiersBank::factory()->create([
            'tiers_id' => $tiers->id,
        ]);
    }

    /**
     * Création des chantiers
     */
    private function seedChantiers(): void
    {
        if (Chantiers::count() > 0) {
            $this->command->warn('⚠️  Les chantiers existent déjà, passage...');

            return;
        }

        $chantiersCount = rand(5, 30);
        $chantiers = Chantiers::factory($chantiersCount)->create([
            'tiers_id' => Tiers::all()->random()->id,
            'responsable_id' => User::first()->id,
        ]);

        foreach ($chantiers as $chantier) {
            $this->createChantierRelations($chantier);
        }

        $this->command->info("✓ {$chantiersCount} chantiers créés avec leurs données");
    }

    /**
     * Création des relations d'un chantier
     */
    private function createChantierRelations(Chantiers $chantier): void
    {
        // Adresse du chantier
        ChantierAddress::factory()->create([
            'chantiers_id' => $chantier->id,
        ]);

        // Dépenses
        ChantierDepense::factory(rand(1, 15))->create([
            'chantiers_id' => $chantier->id,
            'tiers_id' => $chantier->tiers_id,
        ]);

        // Tâches
        ChantierTask::factory(rand(1, 10))->create([
            'chantiers_id' => $chantier->id,
            'assigned_id' => $chantier->responsable_id,
        ]);

        // Interventions
        ChantierIntervention::factory(rand(1, 5))->create([
            'chantiers_id' => $chantier->id,
            'intervenant_id' => $chantier->responsable_id,
        ]);
    }

    /**
     * Création des données commerciales
     */
    private function seedCommerce(): void
    {
        $this->seedDevis();
        $this->seedCommandes();
        $this->seedFactures();
        $this->seedAvoirs();
    }

    /**
     * Création des devis
     */
    private function seedDevis(): void
    {
        if (Devis::count() > 0) {
            $this->command->warn('⚠️  Les devis existent déjà, passage...');

            return;
        }

        $devisCount = rand(1, 10);
        $devis = Devis::factory($devisCount)->create([
            'chantiers_id' => Chantiers::all()->random()->id,
            'tiers_id' => Tiers::all()->random()->id,
            'responsable_id' => User::first()->id,
            'num_devis' => Helpers::generateCodeDevis(),
        ]);

        foreach ($devis as $devi) {
            DevisLigne::factory(rand(1, 5))->create([
                'devis_id' => $devi->id,
            ]);
        }

        $this->command->info("✓ {$devisCount} devis créés avec leurs lignes");
    }

    /**
     * Création des commandes
     */
    private function seedCommandes(): void
    {
        if (Commande::count() > 0) {
            $this->command->warn('⚠️  Les commandes existent déjà, passage...');

            return;
        }

        $commandesCount = rand(1, 10);
        $commandes = Commande::factory($commandesCount)->create([
            'num_commande' => Helpers::generateCodeCommande(),
        ]);

        foreach ($commandes as $commande) {
            CommandeLigne::factory(rand(1, 5))->create([
                'commande_id' => $commande->id,
            ]);
        }

        $this->command->info("✓ {$commandesCount} commandes créées avec leurs lignes");
    }

    /**
     * Création des factures
     */
    private function seedFactures(): void
    {
        if (Facture::count() > 0) {
            $this->command->warn('⚠️  Les factures existent déjà, passage...');

            return;
        }

        $facturesCount = rand(1, 30);
        $factures = Facture::factory($facturesCount)->create([
            'num_facture' => Helpers::generateCodeFacture(),
        ]);

        foreach ($factures as $facture) {
            // Lignes de facture
            FactureLigne::factory(rand(1, 5))->create([
                'facture_id' => $facture->id,
            ]);

            // Paiements pour les factures payées
            if ($facture->status->value === 'payer') {
                FacturePaiement::create([
                    'facture_id' => $facture->id,
                    'date_paiement' => $facture->date_echeance->addDays(rand(2, 5)),
                    'amount' => $facture->amount_ttc,
                    'mode_reglement_id' => ModeReglement::all()->random()->id,
                    'reference' => 'STS'.now()->format('ym').'-00'.rand(10, 99),
                ]);
            }
        }

        $this->command->info("✓ {$facturesCount} factures créées avec leurs lignes et paiements");
    }

    /**
     * Création des avoirs
     */
    private function seedAvoirs(): void
    {
        if (Avoir::count() > 0) {
            $this->command->warn('⚠️  Les avoirs existent déjà, passage...');

            return;
        }

        $facturesPayees = Facture::where('status', 'payer')->get();
        $avoirsCount = 0;

        foreach ($facturesPayees as $facture) {
            $avoir = Avoir::factory()->create([
                'num_avoir' => Helpers::generateCodeAvoir(),
                'facture_id' => $facture->id,
            ]);

            AvoirLigne::factory(rand(1, 5))->create([
                'avoir_id' => $avoir->id,
            ]);

            $avoirsCount++;
        }

        $this->command->info("✓ {$avoirsCount} avoirs créés avec leurs lignes");
    }

    /**
     * Création des profils de paie
     */
    private function seedProfilsPaie(): void
    {
        if (ProfilPaie::count() > 0) {
            $this->command->warn('⚠️  Les profils de paie existent déjà, passage...');

            return;
        }

        $this->call(ProfilPaieSeeder::class);
        $this->command->info('✓ Profils de paie créés');
    }

    /**
     * Création des employés
     */
    private function seedEmployes(): void
    {
        if (Employe::count() > 0) {
            $this->command->warn('⚠️  Les employés existent déjà, passage...');

            return;
        }

        $employesCount = rand(5, 15);
        $contratsCount = 0;
        $infosCount = 0;
        $banksCount = 0;

        for ($i = 0; $i < $employesCount; $i++) {
            // Créer un utilisateur pour l'employé
            $user = User::factory()->create([
                'role' => 'salarie',
                'blocked' => rand(1, 10) <= 8, // 80% des employés ont un compte bloqué (en attente d'activation)
            ]);

            // Créer l'employé
            $employe = Employe::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'uuid' => Str::uuid(),
            ]);

            // Créer le contrat (obligatoire)
            EmployeContrat::factory()->create([
                'employe_id' => $employe->id,
            ]);
            $contratsCount++;

            // Créer les informations complémentaires (80% de chance)
            if (rand(1, 10) <= 8) {
                EmployeInfo::factory()->create([
                    'employe_id' => $employe->id,
                ]);
                $infosCount++;
            }

            // Créer les informations bancaires (70% de chance)
            if (rand(1, 10) <= 7) {
                EmployeBank::factory()->create([
                    'employe_id' => $employe->id,
                ]);
                $banksCount++;
            }
        }

        $this->command->info("✓ {$employesCount} employés créés avec {$contratsCount} contrats, {$infosCount} infos et {$banksCount} comptes bancaires");
    }

    /**
     * Création des notes de frais
     */
    private function seedNotesFrais(): void
    {
        if (NoteFrais::count() > 0) {
            $this->command->warn('⚠️  Les notes de frais existent déjà, passage...');

            return;
        }

        $users = User::all();
        $chantiers = Chantiers::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️  Aucun utilisateur trouvé, impossible de créer des notes de frais');

            return;
        }

        $notesCount = rand(10, 25);
        $detailsCount = 0;

        for ($i = 0; $i < $notesCount; $i++) {
            // Créer une note de frais avec un statut aléatoire
            $noteFrais = NoteFrais::factory()
                ->when(rand(1, 10) <= 2, fn ($factory) => $factory->brouillon())
                ->when(rand(1, 10) <= 3, fn ($factory) => $factory->soumise())
                ->when(rand(1, 10) <= 3, fn ($factory) => $factory->validee())
                ->when(rand(1, 10) <= 1, fn ($factory) => $factory->payee())
                ->when(rand(1, 10) <= 1, fn ($factory) => $factory->refusee())
                ->create([
                    'employe_id' => Employe::all()->random()->id,
                ]);

            // Créer entre 1 et 8 détails de frais pour chaque note
            $nbDetails = rand(1, 8);

            for ($j = 0; $j < $nbDetails; $j++) {
                // Choisir un type de frais aléatoire
                $typeFactories = [
                    'transport',
                    'restauration',
                    'hebergement',
                    'carburant',
                    'materiel',
                ];

                $typeFactory = $typeFactories[array_rand($typeFactories)];

                NoteFraisDetail::factory()
                    ->{$typeFactory}()
                    ->when(
                        ! $chantiers->isEmpty() && rand(1, 10) <= 7,
                        fn ($factory) => $factory->state(['chantier_id' => $chantiers->random()->id])
                    )
                    ->when(rand(1, 10) <= 2, fn ($factory) => $factory->sansJustificatif())
                    ->when(rand(1, 10) <= 1, fn ($factory) => $factory->nonRemboursable())
                    ->create([
                        'note_frais_id' => $noteFrais->id,
                    ]);

                $detailsCount++;
            }
        }

        $this->command->info("✓ {$notesCount} notes de frais créées avec {$detailsCount} détails");
    }

    /**
     * Création des produits
     */
    /**
     * Création des produits, services, stocks, mouvements et tarifs
     */
    private function seedProduits(): void
    {
        if (Produit::count() > 0) {
            $this->command->warn('⚠️  Les produits existent déjà, passage...');
            return;
        }

        $this->command->info('📦 Création des produits, services, stocks, mouvements et tarifs...');

        // Créer d'abord les catégories et entrepôts si nécessaires
        $this->ensureCategoriesAndEntrepots();

        // Récupérer les catégories et entrepôts
        $categories = Category::all();
        $entrepots = Entrepot::all();

        if ($categories->isEmpty() || $entrepots->isEmpty()) {
            $this->command->warn('⚠️  Catégories ou entrepôts manquants, impossible de créer des produits');
            return;
        }

        // 1. Créer les produits
        $this->createSpecificProducts($categories, $entrepots);
        $this->createRandomProducts($categories, $entrepots);

        // 2. Créer les services
        $this->createServices($categories);

        // 3. Créer les stocks pour les produits
        $this->createProductStocks();

        // 4. Créer les mouvements de stock
        $this->createStockMovements();

        // 5. Créer les tarifs client pour produits et services
        $this->createClientPricing();

        // 6. Créer les tarifs fournisseur pour les produits
        $this->createSupplierPricing();

        // Afficher les statistiques finales
        $this->displayCompleteStats();
    }

    /**
     * S'assurer que les catégories et entrepôts existent
     */
    private function ensureCategoriesAndEntrepots(): void
    {
        // Créer des catégories de base si elles n'existent pas
        if (Category::count() === 0) {
            $categories = [
                ['name' => 'Gros Œuvre'],
                ['name' => 'Plomberie'],
                ['name' => 'Électricité'],
                ['name' => 'Outillage'],
                ['name' => 'Finitions'],
            ];

            foreach ($categories as $categoryData) {
                Category::factory()->create($categoryData);
            }
            $this->command->info('✓ Catégories de base créées');
        }

        // Créer un entrepôt principal si aucun n'existe
        if (Entrepot::count() === 0) {
            Entrepot::factory()->create([
                'name' => 'Entrepôt Principal',
                'description' => 'Entrepôt principal de stockage',
            ]);
            $this->command->info('✓ Entrepôt principal créé');
        }
    }

    /**
     * Créer des produits spécifiques
     */
    private function createSpecificProducts($categories, $entrepots): void
    {
        $categorieGrosOeuvre = $categories->where('name', 'like', '%Gros%')->first();
        $categoriePlomberie = $categories->where('name', 'like', '%Plomberie%')->first();
        $categorieElectricite = $categories->where('name', 'like', '%Électricité%')->first();
        $categorieOutillage = $categories->where('name', 'like', '%Outillage%')->first();
        $entrepotPrincipal = $entrepots->where('name', 'like', '%Principal%')->first() ?? $entrepots->first();

        // Utiliser la factory en mode performance pour créer des produits spécifiques
        $produitsSpecifiques = [
            [
                'name' => 'Ciment Portland CEM II 32,5 - Sac 35kg',
                'description' => 'Ciment Portland composé CEM II/A-LL 32,5 R conforme à la norme NF EN 197-1.',
                'category_id' => $categorieGrosOeuvre?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
            [
                'name' => 'Tube PVC évacuation Ø100 - Longueur 3m',
                'description' => 'Tube PVC rigide pour évacuation eaux usées. Diamètre 100mm.',
                'category_id' => $categoriePlomberie?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
            [
                'name' => 'Câble électrique 3G2,5 - Couronne 100m',
                'description' => 'Câble électrique souple 3x2,5mm² avec terre. Isolation PVC.',
                'category_id' => $categorieElectricite?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
            [
                'name' => 'Perceuse visseuse 18V Li-Ion',
                'description' => 'Perceuse visseuse sans fil 18V avec batterie lithium-ion 2Ah.',
                'category_id' => $categorieOutillage?->id ?? $categories->random()->id,
                'entrepot_id' => $entrepotPrincipal->id,
            ],
        ];

        foreach ($produitsSpecifiques as $produitData) {
            Produit::factory()->performance()->create($produitData);
        }

        $this->command->info('✓ 4 produits spécifiques créés');
    }

    /**
     * Créer des produits aléatoires
     */
    private function createRandomProducts($categories, $entrepots): void
    {
        $totalGeneres = 0;

        // 2-3 produits par catégorie principale
        $categoriesPrincipales = $categories->whereNull('category_id');
        foreach ($categoriesPrincipales as $category) {
            $nombreProduits = rand(2, 3);

            Produit::factory()
                ->performance()
                ->count($nombreProduits)
                ->create([
                    'category_id' => $category->id,
                    'entrepot_id' => $entrepots->random()->id,
                ]);

            $totalGeneres += $nombreProduits;
        }

        // Quelques produits spécialisés
        $produitsSpecialises = [
            Produit::factory()->performance()->count(3)->create(), // Matériaux
            Produit::factory()->performance()->count(2)->create(), // Outillage
            Produit::factory()->performance()->count(2)->create(), // Divers
        ];

        $totalSpecialises = 7; // 3 + 2 + 2
        $totalGeneres += $totalSpecialises;

        $this->command->info("✓ {$totalGeneres} produits aléatoires créés");
    }

    /**
     * Créer les services
     */
    private function createServices($categories): void
    {
        if (Service::count() > 0) {
            $this->command->warn('⚠️  Les services existent déjà, passage...');
            return;
        }

        $this->command->info('🔧 Création des services...');

        // Services spécifiques avec données fixes
        $servicesSpecifiques = [
            [
                'reference' => 'SRV-000001',
                'name' => 'Installation plomberie salle de bain complète',
                'description' => 'Service d\'installation complète de plomberie pour salle de bain : pose sanitaires, raccordements eau chaude/froide, évacuations, robinetterie. Main d\'œuvre qualifiée avec garantie 2 ans.',
                'category_id' => $categories->where('name', 'like', '%Plomberie%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000002',
                'name' => 'Installation électrique résidentielle',
                'description' => 'Installation électrique complète pour logement : tableau électrique, circuits prises et éclairage, mise à la terre. Conforme NF C 15-100.',
                'category_id' => $categories->where('name', 'like', '%Électricité%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000003',
                'name' => 'Maçonnerie générale',
                'description' => 'Travaux de maçonnerie générale : montage murs, cloisons, enduits, petites réparations. Matériaux et outillage inclus.',
                'category_id' => $categories->where('name', 'like', '%Gros%')->first()?->id ?? $categories->random()->id,
            ],
            [
                'reference' => 'SRV-000004',
                'name' => 'Diagnostic technique bâtiment',
                'description' => 'Diagnostic complet de l\'état du bâtiment : structure, étanchéité, isolation, installations. Rapport détaillé avec recommandations.',
                'category_id' => $categories->random()->id,
            ],
        ];

        foreach ($servicesSpecifiques as $serviceData) {
            Service::create($serviceData);
            $this->command->info("✅ Service créé : {$serviceData['name']}");
        }

        // Générer 1-2 services par catégorie principale
        $totalServicesGeneres = 0;
        $categoriesPrincipales = $categories->whereNull('category_id');

        foreach ($categoriesPrincipales as $category) {
            $nombreServices = rand(1, 2);

            Service::factory()
                ->count($nombreServices)
                ->pourCategorie($category->id)
                ->create();

            $totalServicesGeneres += $nombreServices;
            $this->command->info("📦 {$nombreServices} services créés pour la catégorie : {$category->name}");
        }

        // Créer quelques services spécialisés
        $servicesSpecialises = [
            Service::factory()->count(2)->construction()->create(),
            Service::factory()->count(2)->renovation()->create(),
        ];

        $totalSpecialises = 4; // 2 + 2
        $totalServicesGeneres += $totalSpecialises;

        $this->command->info("🎯 {$totalSpecialises} services spécialisés créés");
        $this->command->info("✅ Total services créés : " . Service::count());
    }

    /**
     * Créer les stocks pour les produits
     */
    private function createProductStocks(): void
    {
        if (ProduitStock::count() > 0) {
            $this->command->warn('⚠️  Les stocks existent déjà, passage...');
            return;
        }

        $this->command->info('📦 Création des stocks de produits...');

        // Récupérer un échantillon de produits et entrepôts
        $produits = Produit::take(20)->get();
        $entrepots = Entrepot::take(5)->get();

        if ($produits->isEmpty() || $entrepots->isEmpty()) {
            $this->command->warn('⚠️  Produits ou entrepôts manquants pour créer les stocks');
            return;
        }

        $totalStocks = 0;
        $stocksEnRupture = 0;
        $stocksCritiques = 0;
        $stocksNormaux = 0;

        // Créer des stocks pour chaque produit dans 1 à 3 entrepôts
        foreach ($produits as $produit) {
            $entrepotsSelectionnes = $entrepots->random(rand(1, min(3, $entrepots->count())));

            foreach ($entrepotsSelectionnes as $entrepot) {
                $typeStock = $this->determinerTypeStock();

                $stock = match ($typeStock) {
                    'rupture' => ProduitStock::factory()
                        ->enRupture()
                        ->pourProduit($produit)
                        ->pourEntrepot($entrepot)
                        ->create(),
                    'critique' => ProduitStock::factory()
                        ->stockCritique()
                        ->pourProduit($produit)
                        ->pourEntrepot($entrepot)
                        ->create(),
                    'faible' => ProduitStock::factory()
                        ->stockFaible()
                        ->pourProduit($produit)
                        ->pourEntrepot($entrepot)
                        ->create(),
                    'normal' => ProduitStock::factory()
                        ->stockNormal()
                        ->pourProduit($produit)
                        ->pourEntrepot($entrepot)
                        ->create(),
                    'eleve' => ProduitStock::factory()
                        ->stockEleve()
                        ->pourProduit($produit)
                        ->pourEntrepot($entrepot)
                        ->create(),
                };

                $totalStocks++;

                match ($typeStock) {
                    'rupture' => $stocksEnRupture++,
                    'critique' => $stocksCritiques++,
                    default => $stocksNormaux++,
                };
            }
        }

        $this->command->info("✅ {$totalStocks} stocks créés");
        $this->command->info("🔴 Stocks en rupture : {$stocksEnRupture}");
        $this->command->info("🟡 Stocks critiques : {$stocksCritiques}");
        $this->command->info("🟢 Stocks normaux/élevés : {$stocksNormaux}");
    }

    /**
     * Créer les mouvements de stock
     */
    private function createStockMovements(): void
    {
        if (ProduitStockMvm::count() > 0) {
            $this->command->warn('⚠️  Les mouvements de stock existent déjà, passage...');
            return;
        }

        $this->command->info('📊 Création des mouvements de stock...');

        $stocks = ProduitStock::all();

        if ($stocks->isEmpty()) {
            $this->command->warn('⚠️  Aucun stock trouvé pour créer les mouvements');
            return;
        }

        $totalMouvements = 0;
        $entrees = 0;
        $sorties = 0;

        // Créer 2-5 mouvements par stock
        foreach ($stocks as $stock) {
            $nombreMouvements = rand(2, 5);

            for ($i = 0; $i < $nombreMouvements; $i++) {
                // 60% d'entrées, 40% de sorties
                $typeMovement = rand(1, 100) <= 60 ? 'entree' : 'sortie';

                $mouvement = match ($typeMovement) {
                    'entree' => ProduitStockMvm::factory()
                        ->entree()
                        ->pourStock($stock)
                        ->create(),
                    'sortie' => ProduitStockMvm::factory()
                        ->sortie()
                        ->pourStock($stock)
                        ->create(),
                };

                $totalMouvements++;

                if ($typeMovement === 'entree') {
                    $entrees++;
                } else {
                    $sorties++;
                }
            }
        }

        // Créer quelques mouvements spécifiques
        $this->createSpecificMovements();

        $this->command->info("✅ {$totalMouvements} mouvements de base créés");
        $this->command->info("📥 Entrées : {$entrees}");
        $this->command->info("📤 Sorties : {$sorties}");
    }

    /**
     * Créer des mouvements spécifiques
     */
    private function createSpecificMovements(): void
    {
        $stocks = ProduitStock::take(3)->get();

        foreach ($stocks as $stock) {
            // Mouvement d'entrée important
            ProduitStockMvm::factory()
                ->entree()
                ->quantiteImportante()
                ->pourStock($stock)
                ->create([
                    'libelle' => 'Réception commande importante - Test',
                ]);

            // Mouvement de sortie important
            ProduitStockMvm::factory()
                ->sortie()
                ->quantiteImportante()
                ->pourStock($stock)
                ->create([
                    'libelle' => 'Livraison client importante - Test',
                ]);
        }

        $this->command->info("🎯 Mouvements spécifiques créés");
    }

    /**
     * Déterminer le type de stock selon une répartition
     */
    private function determinerTypeStock(): string
    {
        $rand = rand(1, 100);

        return match (true) {
            $rand <= 5 => 'rupture',      // 5%
            $rand <= 15 => 'critique',    // 10%
            $rand <= 30 => 'faible',      // 15%
            $rand <= 80 => 'normal',      // 50%
            default => 'eleve',           // 20%
        };
    }

    /**
     * Créer les tarifs client pour produits et services
     */
    private function createClientPricing(): void
    {
        if (TarifClient::count() > 0) {
            $this->command->warn('⚠️  Les tarifs client existent déjà, passage...');
            return;
        }

        $this->command->info('💰 Création des tarifs client...');

        $produits = Produit::all();
        $services = Service::all();

        $totalTarifsClient = 0;

        // Créer des tarifs spécifiques pour les premiers produits
        $this->createSpecificClientPricing($produits);

        // Créer des tarifs pour 80% des produits
        if (!$produits->isEmpty()) {
            $produitsAvecTarifs = $produits->take(ceil($produits->count() * 0.8));

            foreach ($produitsAvecTarifs as $produit) {
                // Créer 1-2 tarifs par produit (différentes gammes)
                $nombreTarifs = rand(1, 2);

                for ($i = 0; $i < $nombreTarifs; $i++) {
                    TarifClient::factory()
                        ->pourProduit($produit->id)
                        ->create();

                    $totalTarifsClient++;
                }
            }

            $this->command->info("📦 Tarifs client créés pour {$produitsAvecTarifs->count()} produits");
        }

        // Créer des tarifs pour 90% des services
        if (!$services->isEmpty()) {
            $servicesAvecTarifs = $services->take(ceil($services->count() * 0.9));

            foreach ($servicesAvecTarifs as $service) {
                TarifClient::factory()
                    ->pourService($service->id)
                    ->create();

                $totalTarifsClient++;
            }

            $this->command->info("🔧 Tarifs client créés pour {$servicesAvecTarifs->count()} services");
        }

        // Créer quelques tarifs spécialisés
        $this->createSpecializedClientPricing();

        $this->command->info("✅ Total tarifs client créés : " . TarifClient::count());
    }

    /**
     * Créer des tarifs client spécifiques
     */
    private function createSpecificClientPricing($produits): void
    {
        if ($produits->isEmpty()) {
            return;
        }

        // Tarifs spécifiques pour les 4 premiers produits
        $produitsSpecifiques = $produits->take(4);

        $tarifsSpecifiques = [
            [
                'prix_unitaire' => 15.50,
                'taux_tva' => 20.0,
                'produit_id' => $produitsSpecifiques->get(0)?->id,
                'service_id' => null,
            ],
            [
                'prix_unitaire' => 8.75,
                'taux_tva' => 20.0,
                'produit_id' => $produitsSpecifiques->get(1)?->id,
                'service_id' => null,
            ],
            [
                'prix_unitaire' => 2.30,
                'taux_tva' => 20.0,
                'produit_id' => $produitsSpecifiques->get(2)?->id,
                'service_id' => null,
            ],
            [
                'prix_unitaire' => 189.99,
                'taux_tva' => 20.0,
                'produit_id' => $produitsSpecifiques->get(3)?->id,
                'service_id' => null,
            ],
        ];

        foreach ($tarifsSpecifiques as $tarifData) {
            if ($tarifData['produit_id']) {
                TarifClient::create($tarifData);
                $this->command->info("✅ Tarif client spécifique créé : {$tarifData['prix_unitaire']}€");
            }
        }
    }

    /**
     * Créer des tarifs client spécialisés
     */
    private function createSpecializedClientPricing(): void
    {
        $produits = Produit::take(5)->get();
        $services = Service::take(3)->get();

        // Tarifs premium pour certains produits
        foreach ($produits as $produit) {
            TarifClient::factory()
                ->pourProduit($produit->id)
                ->premium()
                ->create();
        }

        // Tarifs économiques pour certains services
        foreach ($services as $service) {
            TarifClient::factory()
                ->pourService($service->id)
                ->economique()
                ->create();
        }

        $this->command->info("🎯 Tarifs client spécialisés créés");
    }

    /**
     * Créer les tarifs fournisseur pour les produits
     */
    private function createSupplierPricing(): void
    {
        if (TarifFournisseur::count() > 0) {
            $this->command->warn('⚠️  Les tarifs fournisseur existent déjà, passage...');
            return;
        }

        $this->command->info('🏭 Création des tarifs fournisseur...');

        $produits = Produit::all();

        if ($produits->isEmpty()) {
            $this->command->warn('⚠️  Aucun produit trouvé pour créer les tarifs fournisseur');
            return;
        }

        $totalTarifsFournisseur = 0;

        // Créer des tarifs spécifiques pour les premiers produits
        $this->createSpecificSupplierPricing($produits);

        // Créer des tarifs pour 70% des produits (pas tous les produits ont un fournisseur)
        $produitsAvecTarifs = $produits->take(ceil($produits->count() * 0.7));

        foreach ($produitsAvecTarifs as $produit) {
            // 60% de chance d'avoir un tarif fournisseur
            if (rand(1, 100) <= 60) {
                TarifFournisseur::factory()
                    ->pourProduit($produit->id)
                    ->create();

                $totalTarifsFournisseur++;
            }
        }

        // Créer quelques tarifs spécialisés
        $this->createSpecializedSupplierPricing();

        $this->command->info("✅ {$totalTarifsFournisseur} tarifs fournisseur créés");
        $this->command->info("✅ Total tarifs fournisseur : " . TarifFournisseur::count());
    }

    /**
     * Créer des tarifs fournisseur spécifiques
     */
    private function createSpecificSupplierPricing($produits): void
    {
        if ($produits->isEmpty()) {
            return;
        }

        // Tarifs spécifiques pour les 4 premiers produits
        $produitsSpecifiques = $produits->take(4);

        $tarifsSpecifiques = [
            [
                'ref_fournisseur' => 'FOUR-CIM-001',
                'qte_minimal' => 10.0,
                'prix_unitaire' => 12.50,
                'delai_livraison' => 3,
                'barrecode' => '3760123456789',
                'produit_id' => $produitsSpecifiques->get(0)?->id,
            ],
            [
                'ref_fournisseur' => 'FOUR-PVC-002',
                'qte_minimal' => 5.0,
                'prix_unitaire' => 6.80,
                'delai_livraison' => 5,
                'barrecode' => '3760123456796',
                'produit_id' => $produitsSpecifiques->get(1)?->id,
            ],
            [
                'ref_fournisseur' => 'FOUR-CAB-003',
                'qte_minimal' => 1.0,
                'prix_unitaire' => 1.85,
                'delai_livraison' => 7,
                'barrecode' => '3760123456803',
                'produit_id' => $produitsSpecifiques->get(2)?->id,
            ],
            [
                'ref_fournisseur' => 'FOUR-OUT-004',
                'qte_minimal' => 1.0,
                'prix_unitaire' => 145.00,
                'delai_livraison' => 10,
                'barrecode' => '3760123456810',
                'produit_id' => $produitsSpecifiques->get(3)?->id,
            ],
        ];

        foreach ($tarifsSpecifiques as $tarifData) {
            if ($tarifData['produit_id']) {
                TarifFournisseur::create($tarifData);
                $this->command->info("✅ Tarif fournisseur spécifique créé : {$tarifData['ref_fournisseur']}");
            }
        }
    }

    /**
     * Créer des tarifs fournisseur spécialisés
     */
    private function createSpecializedSupplierPricing(): void
    {
        $produits = Produit::take(5)->get();

        // Tarifs avec livraison rapide
        foreach ($produits->take(3) as $produit) {
            TarifFournisseur::factory()
                ->pourProduit($produit->id)
                ->livraisonRapide()
                ->create();
        }

        // Tarifs économiques (au lieu de quantité minimale faible)
        foreach ($produits->skip(3)->take(2) as $produit) {
            TarifFournisseur::factory()
                ->pourProduit($produit->id)
                ->economique()
                ->create();
        }

        $this->command->info("🎯 Tarifs fournisseur spécialisés créés");
    }

    /**
     * Afficher les statistiques complètes
     */
    private function displayCompleteStats(): void
    {
        $totalProduits = Produit::count();
        $totalServices = Service::count();
        $totalStocks = ProduitStock::count();
        $totalMouvements = ProduitStockMvm::count();
        $totalTarifsClient = TarifClient::count();
        $totalTarifsFournisseur = TarifFournisseur::count();
        $entreesFinales = ProduitStockMvm::entrees()->count();
        $sortiesFinales = ProduitStockMvm::sorties()->count();
        $tarifsClientProduits = TarifClient::pourProduits()->count();
        $tarifsClientServices = TarifClient::pourServices()->count();

        $this->command->info('📊 === STATISTIQUES COMPLÈTES ===');
        $this->command->info("📦 Produits créés : {$totalProduits}");
        $this->command->info("🔧 Services créés : {$totalServices}");
        $this->command->info("📦 Stocks créés : {$totalStocks}");
        $this->command->info("📊 Mouvements créés : {$totalMouvements}");
        $this->command->info("📥 - Entrées : {$entreesFinales}");
        $this->command->info("📤 - Sorties : {$sortiesFinales}");
        $this->command->info("💰 Tarifs client créés : {$totalTarifsClient}");
        $this->command->info("📦 - Pour produits : {$tarifsClientProduits}");
        $this->command->info("🔧 - Pour services : {$tarifsClientServices}");
        $this->command->info("🏭 Tarifs fournisseur créés : {$totalTarifsFournisseur}");
        $this->command->info('✅ Seeding complet terminé !');
    }
}
