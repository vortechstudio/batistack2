<?php

declare(strict_types=1);

namespace App\Enums\Produits;

enum TypeProduit: string
{
    case MATERIAU = 'materiau';
    case OUTILLAGE = 'outillage';
    case MAIN_OEUVRE = 'main_oeuvre';
    case LOCATION = 'location';
    case TRANSPORT = 'transport';
    case ETUDE = 'etude';
    case CONSEIL = 'conseil';
    case FORFAIT = 'forfait';
    case CONSOMMABLE = 'consommable';

    /**
     * Obtenir le libellé du type de produit
     */
    public function getLibelle(): string
    {
        return match ($this) {
            self::MATERIAU => 'Matériau',
            self::OUTILLAGE => 'Outillage',
            self::MAIN_OEUVRE => 'Main d\'œuvre',
            self::LOCATION => 'Location',
            self::TRANSPORT => 'Transport',
            self::ETUDE => 'Étude',
            self::CONSEIL => 'Conseil',
            self::FORFAIT => 'Forfait',
            self::CONSOMMABLE => 'Consommable',
        };
    }

    /**
     * Obtenir la couleur associée au type
     */
    public function getCouleur(): string
    {
        return match ($this) {
            self::MATERIAU => '#3B82F6', // Bleu
            self::OUTILLAGE => '#8B5CF6', // Violet
            self::MAIN_OEUVRE => '#10B981', // Vert
            self::LOCATION => '#F59E0B', // Orange
            self::TRANSPORT => '#EF4444', // Rouge
            self::ETUDE => '#6366F1', // Indigo
            self::CONSEIL => '#EC4899', // Rose
            self::FORFAIT => '#14B8A6', // Teal
            self::CONSOMMABLE => '#84CC16', // Lime
        };
    }

    /**
     * Vérifier si le type est un service
     */
    public function isService(): bool
    {
        return in_array($this, [
            self::MAIN_OEUVRE,
            self::LOCATION,
            self::TRANSPORT,
            self::ETUDE,
            self::CONSEIL,
            self::FORFAIT,
        ]);
    }

    /**
     * Vérifier si le type est un matériel
     */
    public function isMateriel(): bool
    {
        return in_array($this, [
            self::MATERIAU,
            self::OUTILLAGE,
            self::CONSOMMABLE,
        ]);
    }

    /**
     * Obtenir tous les types de services
     */
    public static function getServices(): array
    {
        return [
            self::MAIN_OEUVRE,
            self::LOCATION,
            self::TRANSPORT,
            self::ETUDE,
            self::CONSEIL,
            self::FORFAIT,
        ];
    }

    /**
     * Obtenir tous les types de matériels
     */
    public static function getMateriels(): array
    {
        return [
            self::MATERIAU,
            self::OUTILLAGE,
            self::CONSOMMABLE,
        ];
    }

    /**
     * Obtenir tous les cas sous forme de tableau associatif
     */
    public static function toArray(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->value] = $case->getLibelle();
        }
        return $cases;
    }

    /**
     * Obtenir tous les services sous forme de tableau associatif
     */
    public static function getServicesArray(): array
    {
        $services = [];
        foreach (self::getServices() as $service) {
            $services[$service->value] = $service->getLibelle();
        }
        return $services;
    }

    /**
     * Obtenir tous les matériels sous forme de tableau associatif
     */
    public static function getMaterielsArray(): array
    {
        $materiels = [];
        foreach (self::getMateriels() as $materiel) {
            $materiels[$materiel->value] = $materiel->getLibelle();
        }
        return $materiels;
    }

    /**
     * Obtenir la description du type
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::MATERIAU => 'Matériaux de construction, fournitures',
            self::OUTILLAGE => 'Outils, équipements, machines',
            self::MAIN_OEUVRE => 'Prestations de main d\'œuvre',
            self::LOCATION => 'Location d\'équipements, véhicules',
            self::TRANSPORT => 'Transport, livraison, déplacement',
            self::ETUDE => 'Études techniques, diagnostics',
            self::CONSEIL => 'Conseil, expertise, formation',
            self::FORFAIT => 'Prestations forfaitaires',
            self::CONSOMMABLE => 'Produits consommables, petites fournitures',
        };
    }

    /**
     * Obtenir l'icône associée au type
     */
    public function getIcone(): string
    {
        return match ($this) {
            self::MATERIAU => 'heroicon-o-cube',
            self::OUTILLAGE => 'heroicon-o-wrench-screwdriver',
            self::MAIN_OEUVRE => 'heroicon-o-user-group',
            self::LOCATION => 'heroicon-o-truck',
            self::TRANSPORT => 'heroicon-o-arrow-path',
            self::ETUDE => 'heroicon-o-document-magnifying-glass',
            self::CONSEIL => 'heroicon-o-chat-bubble-left-right',
            self::FORFAIT => 'heroicon-o-rectangle-group',
            self::CONSOMMABLE => 'heroicon-o-squares-2x2',
        };
    }
}
