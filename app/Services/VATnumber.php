<?php

namespace App\Services;

use PH7\Eu\Vat\Provider\Europa;
use PH7\Eu\Vat\Validator;

class VATnumber
{
    /**
     * Vérifie la validité d'un numéro TVA
     *
     * @param  string  $number  Numéro TVA à valider
     * @param  string  $country_code  Code pays sur 2 caractères (ex: FR)
     * @return bool True si le numéro est valide, False sinon
     *
     * @throws \Exception En cas d'erreur de communication avec le service de validation
     */
    public function verify(string $number, string $country_code): bool
    {
        $call = new Validator(new Europa, $number, $country_code);

        return $call->check();
    }

    public function info(string $number, string $country_code): ?\Illuminate\Support\Collection
    {
        $call = new Validator(new Europa, $number, $country_code);

        if ($call->check()) {
            return collect([
                'name' => $call->getName(),
                'address' => $call->getAddress(),
                'Vat_number' => $call->getVatNumber(),
            ]);
        } else {
            return null;
        }
    }
}
