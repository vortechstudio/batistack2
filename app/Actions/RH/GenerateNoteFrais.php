<?php

declare(strict_types=1);

namespace App\Actions\RH;

use App\Enums\Chantiers\TypeDepenseChantier;
use App\Models\Chantiers\Chantiers;
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
                ->quantity(1)
                ->pricePerUnit((float) $detail->montant_ht)
                ->tax((float) $detail->montant_tva);
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
            ->filename($frais->employe->matricule.'/documents/frais/'.now()->year.'/'.now()->month.'/receipt_'.$frais->numero)
            ->addItems($items)
            ->notes($notes)
            ->save('ged');

        $this->addingFraisChantierDepense($frais);

        return $invoice;
    }

    private function addingFraisChantierDepense(NoteFrais $frais)
    {
        foreach ($frais->details as $detail) {
            if (isset($detail->chantier_id)) {
                $chantier = Chantiers::find($detail->chantier_id);
                $chantier->depenses()->create([
                    'type_depense' => TypeDepenseChantier::Frais,
                    'description' => $detail->libelle,
                    'montant' => $detail->montant_ht,
                    'date_depense' => $frais->date_validation,
                    'invoice_ref' => $detail->numero_facture ?? null,
                    'tiers_id' => $frais->employe->id,
                    'chantiers_id' => $detail->chantier_id,
                ]);
            }
        }
    }
}
