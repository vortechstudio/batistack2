<?php

declare(strict_types=1);

namespace Database\Seeders\Produit;

use App\Models\Produit\UniteMesure;
use Illuminate\Database\Seeder;

final class UniteMesureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📏 Création des unités de mesure...');

        if (UniteMesure::count() > 0) {
            $this->command->warn('⚠️  Les unités de mesure existent déjà, passage...');
            return;
        }

        // Créer les unités de base
        $unitesBase = $this->getUnitesBase();
        $unitesCreees = [];

        foreach ($unitesBase as $uniteData) {
            $unite = UniteMesure::factory()->base()->create($uniteData);
            $unitesCreees[$uniteData['code']] = $unite;
        }

        // Créer les unités dérivées
        $this->createUnitesDerivees($unitesCreees);

        $this->command->info('✓ Unités de mesure créées avec succès');
    }

    /**
     * Obtenir les unités de base
     */
    private function getUnitesBase(): array
    {
        return [
            // Longueur
            [
                'code' => 'M',
                'nom' => 'Mètre',
                'symbole' => 'm',
                'type' => 'longueur',
                'description' => 'Unité de base pour les longueurs',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
            // Masse
            [
                'code' => 'KG',
                'nom' => 'Kilogramme',
                'symbole' => 'kg',
                'type' => 'masse',
                'description' => 'Unité de base pour les masses',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
            // Volume
            [
                'code' => 'M3',
                'nom' => 'Mètre cube',
                'symbole' => 'm³',
                'type' => 'volume',
                'description' => 'Unité de base pour les volumes',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
            // Surface
            [
                'code' => 'M2',
                'nom' => 'Mètre carré',
                'symbole' => 'm²',
                'type' => 'surface',
                'description' => 'Unité de base pour les surfaces',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
            // Temps
            [
                'code' => 'H',
                'nom' => 'Heure',
                'symbole' => 'h',
                'type' => 'temps',
                'description' => 'Unité de base pour le temps',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
            // Quantité
            [
                'code' => 'U',
                'nom' => 'Unité',
                'symbole' => 'u',
                'type' => 'quantite',
                'description' => 'Unité de base pour les quantités',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
            // Énergie
            [
                'code' => 'KWH',
                'nom' => 'Kilowatt-heure',
                'symbole' => 'kWh',
                'type' => 'energie',
                'description' => 'Unité de base pour l\'énergie',
                'facteur_conversion' => 1.0,
                'unite_base_id' => null,
            ],
        ];
    }

    /**
     * Créer les unités dérivées
     */
    private function createUnitesDerivees(array $unitesBase): void
    {
        $unitesDerivees = [
            // Dérivées de longueur (mètre)
            [
                'code' => 'MM',
                'nom' => 'Millimètre',
                'symbole' => 'mm',
                'type' => 'longueur',
                'description' => 'Millimètre',
                'facteur_conversion' => 0.001,
                'unite_base' => 'M',
            ],
            [
                'code' => 'CM',
                'nom' => 'Centimètre',
                'symbole' => 'cm',
                'type' => 'longueur',
                'description' => 'Centimètre',
                'facteur_conversion' => 0.01,
                'unite_base' => 'M',
            ],
            [
                'code' => 'KM',
                'nom' => 'Kilomètre',
                'symbole' => 'km',
                'type' => 'longueur',
                'description' => 'Kilomètre',
                'facteur_conversion' => 1000.0,
                'unite_base' => 'M',
            ],

            // Dérivées de masse (kilogramme)
            [
                'code' => 'G',
                'nom' => 'Gramme',
                'symbole' => 'g',
                'type' => 'masse',
                'description' => 'Gramme',
                'facteur_conversion' => 0.001,
                'unite_base' => 'KG',
            ],
            [
                'code' => 'T',
                'nom' => 'Tonne',
                'symbole' => 't',
                'type' => 'masse',
                'description' => 'Tonne métrique',
                'facteur_conversion' => 1000.0,
                'unite_base' => 'KG',
            ],

            // Dérivées de volume (mètre cube)
            [
                'code' => 'L',
                'nom' => 'Litre',
                'symbole' => 'l',
                'type' => 'volume',
                'description' => 'Litre',
                'facteur_conversion' => 0.001,
                'unite_base' => 'M3',
            ],
            [
                'code' => 'MLI',
                'nom' => 'Millilitre',
                'symbole' => 'ml',
                'type' => 'volume',
                'description' => 'Millilitre',
                'facteur_conversion' => 0.000001,
                'unite_base' => 'M3',
            ],

            // Dérivées de surface (mètre carré)
            [
                'code' => 'CM2',
                'nom' => 'Centimètre carré',
                'symbole' => 'cm²',
                'type' => 'surface',
                'description' => 'Centimètre carré',
                'facteur_conversion' => 0.0001,
                'unite_base' => 'M2',
            ],

            // Dérivées de temps (heure)
            [
                'code' => 'MIN',
                'nom' => 'Minute',
                'symbole' => 'min',
                'type' => 'temps',
                'description' => 'Minute',
                'facteur_conversion' => 0.0167, // 1/60
                'unite_base' => 'H',
            ],
            [
                'code' => 'J',
                'nom' => 'Jour',
                'symbole' => 'j',
                'type' => 'temps',
                'description' => 'Jour',
                'facteur_conversion' => 24.0,
                'unite_base' => 'H',
            ],

            // Unités spécifiques BTP
            [
                'code' => 'ML',
                'nom' => 'Mètre linéaire',
                'symbole' => 'ml',
                'type' => 'longueur',
                'description' => 'Mètre linéaire',
                'facteur_conversion' => 1.0,
                'unite_base' => 'M',
            ],
            [
                'code' => 'PAL',
                'nom' => 'Palette',
                'symbole' => 'pal',
                'type' => 'quantite',
                'description' => 'Palette',
                'facteur_conversion' => 1.0,
                'unite_base' => 'U',
            ],
            [
                'code' => 'SAC',
                'nom' => 'Sac',
                'symbole' => 'sac',
                'type' => 'quantite',
                'description' => 'Sac',
                'facteur_conversion' => 1.0,
                'unite_base' => 'U',
            ],
            [
                'code' => 'LOT',
                'nom' => 'Lot',
                'symbole' => 'lot',
                'type' => 'quantite',
                'description' => 'Lot',
                'facteur_conversion' => 1.0,
                'unite_base' => 'U',
            ],
            [
                'code' => 'KIT',
                'nom' => 'Kit',
                'symbole' => 'kit',
                'type' => 'quantite',
                'description' => 'Kit',
                'facteur_conversion' => 1.0,
                'unite_base' => 'U',
            ],
        ];

        foreach ($unitesDerivees as $uniteData) {
            $uniteBaseCode = $uniteData['unite_base'];
            unset($uniteData['unite_base']);

            if (isset($unitesBase[$uniteBaseCode])) {
                $uniteData['unite_base_id'] = $unitesBase[$uniteBaseCode]->id;
                UniteMesure::factory()->derivee()->create($uniteData);
            }
        }
    }
}
