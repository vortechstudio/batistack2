<?php

declare(strict_types=1);

namespace App\Enums\RH;

enum ModePaiementFrais: string
{
    case ESPECES = 'especes';
    case CARTE_BANCAIRE = 'carte';
    case CHEQUE = 'cheque';
    case VIREMENT = 'virement';
    case CARTE_ENTREPRISE = 'carte_entreprise';
    case AVANCE = 'avance';
    case AUTRE = 'autre';

    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($mode) => [$mode->value => $mode->label()])
            ->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::ESPECES => 'Espèces',
            self::CARTE_BANCAIRE => 'Carte bancaire',
            self::CHEQUE => 'Chèque',
            self::VIREMENT => 'Virement',
            self::CARTE_ENTREPRISE => 'Carte entreprise',
            self::AVANCE => 'Avance sur frais',
            self::AUTRE => 'Autre',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ESPECES => 'heroicon-o-banknotes',
            self::CARTE_BANCAIRE => 'heroicon-o-credit-card',
            self::CHEQUE => 'heroicon-o-document-text',
            self::VIREMENT => 'heroicon-o-arrow-right-circle',
            self::CARTE_ENTREPRISE => 'heroicon-o-building-office',
            self::AVANCE => 'heroicon-o-arrow-up-circle',
            self::AUTRE => 'heroicon-o-question-mark-circle',
        };
    }
}
