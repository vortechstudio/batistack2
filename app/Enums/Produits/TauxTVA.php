<?php

namespace App\Enums\Produits;

enum TauxTVA: string
{
    case ZERO = '0.0';
    case REDUIT_2_1 = '2.1';
    case REDUIT_5_5 = '5.5';
    case INTERMEDIAIRE = '10.0';
    case NORMAL = '20.0';

    public function label(): string
    {
        return match($this) {
            self::ZERO => 'Exonéré (0%)',
            self::REDUIT_2_1 => 'Taux réduit (2,1%)',
            self::REDUIT_5_5 => 'Taux réduit (5,5%)',
            self::INTERMEDIAIRE => 'Taux intermédiaire (10%)',
            self::NORMAL => 'Taux normal (20%)',
        };
    }

    public function percentage(): string
    {
        return $this->value . '%';
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::NORMAL;
    }

    /**
     * Taux de TVA pour les travaux de rénovation énergétique
     */
    public static function renovationEnergetique(): self
    {
        return self::REDUIT_5_5;
    }

    /**
     * Taux de TVA pour les travaux d'amélioration
     */
    public static function amelioration(): self
    {
        return self::INTERMEDIAIRE;
    }

    /**
     * Calcule le montant TTC à partir du montant HT
     */
    public function calculerTTC(float $montantHT): float
    {
        return $montantHT * (1 + (float)$this->value / 100);
    }

    /**
     * Calcule le montant de TVA à partir du montant HT
     */
    public function calculerMontantTVA(float $montantHT): float
    {
        return $montantHT * ((float)$this->value / 100);
    }

    /**
     * Obtient la valeur numérique du taux
     */
    public function getNumericValue(): float
    {
        return (float)$this->value;
    }
}
