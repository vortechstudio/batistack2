<div>
    <div class="flex justify-end gap-5 p-5 bg-gray-100 rounded mb-5">
        {{ $this->sendMailAction }}

        {{ $this->frais->est_modifiable ? $this->editAction : null }}
        {{ $this->frais->est_validable ? $this->validateAction : null }}

        {{ $this->frais->est_modifiable ? $this->deleteAction : null }}
    </div>
    <div class="flex justify-between items-center mb-10">
        <div class="flex items-center">
            <x-mary-icon name="o-wallet" class="w-15 h-15 bg-orange-500 text-white p-2 rounded-xl" />
            <div class="ml-4">
                <div class="text-xl font-bold">
                    {{ $frais->numero }}
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <x-ui.badge :color="$frais->statut->color()" :text="$frais->statut->label()" size="xl" />
            <p>Pas encore en comptabilité</p>
        </div>
    </div>
    <div class="flex justify-between gap-5">
        <div class="flex w-1/2 bg-gray-100 rounded p-5">
            <div class="overflow-x-auto">
                <table class="table w-[100%]">
                    <tbody>
                        <tr>
                            <td class="font-bold">Salarié</td>
                            <td class="text-right">{{ $frais->employe->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Période</td>
                            <td class="text-right">{{ $frais->date_debut->format("d/m/Y") }} au {{ $frais->date_fin->format("d/m/Y") }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Date de Soumission</td>
                            <td class="text-right">{{ $frais->date_soumission->format("d/m/Y") }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Date de Validation</td>
                            <td class="text-right">{{ $frais->date_validation->format("d/m/Y") }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex flex-col w-1/2 bg-gray-100 rounded p-5">
            <div class="overflow-x-auto mb-5">
                <table class="table w-[100%]">
                    <tbody>
                        <tr>
                            <td class="font-bold">Montant HT</td>
                            <td class="text-right">{{ Number::currency($frais->montant_total_ht, 'EUR', 'fr_FR') }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Montant TVA</td>
                            <td class="text-right">{{ Number::currency($frais->montant_total_tva, 'EUR', 'fr_FR') }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Montant TTC</td>
                            <td class="text-right">{{ Number::currency($frais->montant_total_calcule, 'EUR', 'fr_FR') }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-lg">Montant Valider</td>
                            <td class="text-right text-lg {{ $frais->montant_valide ? 'text-green-500' : 'text-red-500' }}">{{ $frais->montant_valide ? Number::currency($frais->montant_valide, 'EUR', 'fr_FR') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-lg">Montant Payé</td>
                            <td class="text-right text-lg {{ $frais->montant_paiement ? 'text-green-500' : 'text-red-500' }}">{{ $frais->montant_paiement ? Number::currency($frais->montant_paiement, 'EUR', 'fr_FR') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-[100%] border">
                    <thead>
                        <tr class="bg-gray-200 text-white">
                            <th>Règlement</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Compte Bancaire</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($frais->paiement)
                            <tr>
                                <td>{{ $frais->paiement->numero_paiement }}</td>
                                <td>{{ $frais->paiement->date_paiement->format("d/m/Y") }}</td>
                                <td>{{ $frais->paiement->type_paiement }}</td>
                                <td>{{ $frais->paiement->compte_bancaire }}</td>
                                <td>{{ Number::currency($frais->paiement->montant, 'EUR', 'fr_FR') }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Aucun paiement effectué</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-filament-actions::modals />
</div>
