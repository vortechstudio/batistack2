<?php

namespace App\Enums\Produits;

enum TypeMouvementStock: string
{
    case ENTREE = 'entree';
    case SORTIE = 'sortie';

    /**
     * Obtenir le libellé français du type de mouvement
     */
    public function getLabel(): string
    {
        return match($this) {
            self::ENTREE => 'Entrée',
            self::SORTIE => 'Sortie',
        };
    }

    /**
     * Obtenir l'icône du type de mouvement
     */
    public function getIcon(): string
    {
        return match($this) {
            self::ENTREE => '📥',
            self::SORTIE => '📤',
        };
    }

    /**
     * Obtenir la couleur du type de mouvement
     */
    public function getColor(): string
    {
        return match($this) {
            self::ENTREE => 'success',
            self::SORTIE => 'warning',
        };
    }

    /**
     * Vérifier si c'est une entrée
     */
    public function isEntree(): bool
    {
        return $this === self::ENTREE;
    }

    /**
     * Vérifier si c'est une sortie
     */
    public function isSortie(): bool
    {
        return $this === self::SORTIE;
    }

    /**
     * Obtenir tous les types avec leurs labels
     */
    public static function getOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }

    /**
     * Obtenir le multiplicateur pour le calcul de stock
     * +1 pour les entrées, -1 pour les sorties
     */
    public function getMultiplier(): int
    {
        return match($this) {
            self::ENTREE => 1,
            self::SORTIE => -1,
        };
    }
}
