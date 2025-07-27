<?php

declare(strict_types=1);

namespace App\Actions\RH;

use App\Models\Core\Company;
use App\Models\RH\NoteFrais;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;

final class GenerateNoteFrais
{
    public function handle(NoteFrais $frais)
    {
        $company = Company::first();
        $seller = new Party([
            'name' => $company->name,
            'address' => $company->address.', '.$company->code_postal.' '.$company->ville,
            'phone' => $company->phone,
            'vat' => $company->num_tva,
            'custom_fields' => [
                'siret' => $company->siret,
                'ape' => $company->ape,
            ],
        ]);

        $buyer = new Party([
            'name' => $frais->employe->full_name,
            'address' => $frais->employe->adresse.', '.$frais->employe->code_postal.' '.$frais->employe->ville,
            'custom_fields' => [
                'matricule' => $frais->employe->matricule,
            ],
        ]);

        $items = [];
        foreach ($frais->details as $detail) {
            $items[] = InvoiceItem::make($detail->libelle)
                ->description($frais->type_frais->label())
                ->quantity(1)
                ->pricePerUnit($detail->montant_ht)
                ->tax($detail->taux_tva);
        }

        $notes = [
            'Le paiement de la note de frais se fait 14 jours après la validation',
        ];
        $notes = implode('<br>', $notes);

        $invoice = Invoice::make('receipt')
            ->status($frais->statut->label())
            ->serialNumberFormat($frais->numero)
            ->seller($seller)
            ->buyer($buyer)
            ->date($frais->date_validation)
            ->dateFormat('d/m/Y')
            ->payUntilDays(14)
            ->filename('receipt_'.$frais->numero)
            ->addItems($items)
            ->notes($notes)
            ->save('public');

        return $invoice;
    }
}
