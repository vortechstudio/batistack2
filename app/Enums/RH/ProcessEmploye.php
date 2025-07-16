<?php

namespace App\Enums\RH;

enum ProcessEmploye: string
{
    case CREATING = "creating";
    case VALIDATING = "validating";
    case DPAE = "dpae";
    case SENDING_EXP = "sending_exp";
    case CONTRACT_DRAFT = "contract_draft";
    case CONTRACT_SIGN = "contract_sign";
    case CONTRACT_VALIDATE = "contract_validate";
    case TERMINATED = "terminated";

    public function label()
    {
        return match($this) {
            self::CREATING => "Création",
            self::VALIDATING => "Validation",
            self::DPAE => "DPAE",
            self::SENDING_EXP => "Envoi à l'expert",
            self::CONTRACT_DRAFT => "Brouillon de contrat",
            self::CONTRACT_SIGN => "Signature de contrat",
            self::CONTRACT_VALIDATE => "Validation de contrat",
            self::TERMINATED => "Terminé",
        };
    }

    public function color()
    {
        return match($this) {
            self::CREATING, self::DPAE, self::SENDING_EXP => "blue",
            self::VALIDATING => "amber",
            self::CONTRACT_DRAFT => "gray",
            self::CONTRACT_SIGN, self::CONTRACT_VALIDATE, self::TERMINATED => "green",
        };
    }

    public function description()
    {
        return match($this) {
            self::CREATING => "Création de la fiche utilisateur",
            self::VALIDATING => "Validation des éléments transmis",
            self::DPAE => "Traitement de la DPAE",
            self::SENDING_EXP => "Envoi de la fiche à l'expert",
            self::CONTRACT_DRAFT => "Etablissement du contrat",
            self::CONTRACT_SIGN => "Signature de contrat",
            self::CONTRACT_VALIDATE => "Vérification final",
            self::TERMINATED => "Terminé",
        };
    }

}
