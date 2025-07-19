<div>
    @if($salarie->info->process->value === 'creating')
        <x-mary-alert
            title="Salarié en cours de création"
            description="Veillez à récupérer les éléments nécessaire à la DPAE et au dossier du nouveau salariés (CNI, Carte BTP, Carte Vital, Rib)"
            icon="s-exclamation-triangle"
            class="alert-info">
            <x-slot:actions>
                <x-mary-button label="Transmettre les documents" link="{{ route('humans.salaries.transmission', $salarie->id) }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
    @if($salarie->info->process->value === 'sending_exp')
        <x-mary-alert
            title="DPAE en cours de transmission"
            description="Lorsque votre expert vous transmettra les documents (DPAE, Contrat de travail), Veuillez les transmettres par le service 'transmission'"
            icon="s-exclamation-triangle"
            class="alert-info">
            <x-slot:actions>
                <x-mary-button label="Transmettre les documents" link="{{ route('humans.salaries.transmission', $salarie->id) }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
    @if($salarie->info->process->value === 'contract_draft')
        <x-mary-card title="Contrat de Travail" subtitle="Veuillez vérifier le contrat de travail pour le salarié et signé numériquement celui-ci" shadow separator>
            <div class="h-[450px] mb-5">
                <iframe src="https://docs.google.com/gview?url={{ Storage::disk('public')->url('rh/salarie/'.$salarie->id.'/documents/contract.pdf') }}&embedded=true" class="w-full h-full" frameborder="0"></iframe>
            </div>
            <form wire:submit="validateContract">
                {{ $this->contractValidatedForm }}

                <div class="flex justify-end">
                    <x-mary-button type="submit" class="btn-primary" label="Valider le contrat" spinner="validateContract" />
                </div>
            </form>
        </x-mary-card>
    @endif
    <div class="flex flex-col bg-gray-100 rounded p-5 mb-10" x-data="{tabs: 'user'}">
        <div class="flex flex-row justify-between items-center">
            <x-mary-avatar :image="$salarie->avatar" class="!w-22">
                <x-slot:title class="text-3xl !font-bold pl-2">
                    {{ $salarie->civility }} {{ $salarie->nom }} {{ $salarie->prenom }}
                </x-slot:title>
                <x-slot:subtitle class="flex items-center mt-2 pl-2 text-sm">
                    @svg('heroicon-o-map-pin', ['class' => 'w-5 h-5 text-gray-300']) {{ $salarie->full_address }}
                </x-slot:subtitle>
            </x-mary-avatar>
            <div class="flex flex-col gap-2">
                <x-ui.badge :color="$salarie->status->color()" size="md" :text="$salarie->status->label()" />
                <x-ui.badge :color="$salarie->info->process->color()" size="md" :text="$salarie->info->process->label()" />
                @if($salarie->info->process->value === 'contract_sign')
                    <strong>Début du contrat: </strong> {{ $salarie->contrat->date_debut->diffForHumans() }}
                @endif
            </div>
        </div>
        <div class="flex flex-row justify-between items-center mb-10">
            <div role="tablist" class="tabs tabs-border">
                <a role="tab" :class="tabs === 'user' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'user'">Utilisateur</a>
                <a role="tab" :class="tabs === 'contract' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'contract'">Contrats</a>
                <a role="tab" :class="tabs === 'rh' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'rh'">RH & Banques</a>
                <a role="tab" :class="tabs === 'planning' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'planning'">Plannings</a>
            </div>
            <!--Action-->
        </div>
        <div class="bg-white rounded m-5 p-5">
            <div x-show="tabs === 'user'">
                <div class="flex flex-row gap-5 justify-between">
                    <div class="flex flex-col w-1/2 gap-5">
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Matricule: </strong> {{ $salarie->matricule }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Poste/Fonction: </strong> {{ $salarie->poste }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Heure de travail (Semaine): </strong> {{ $salarie->contrat->heure_travail }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Salaire: </strong> {{ Number::currency($salarie->salaire_base, 'EUR', 'fr') }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong class="tooltip" data-tip="Cette valeur sert dans le calcul des heures facturables sur un planning particulier">Tarif Horaire Moyen: <x-mary-icon name="s-information-circle" class="w-5 h-5 text-gray-200" /> </strong> {{ Number::currency($salarie->contrat->salaire_horaire, 'EUR', 'fr') }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Tarif Journalier moyen: </strong> {{ Number::currency($salarie->contrat->salaire_horaire*8.5, 'EUR', 'fr') }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Date d'embauche: </strong> {{ $salarie->date_embauche->format("d/m/Y") }}
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Date de Naissance: </strong> {{ $salarie->info->date_naissance->format("d/m/Y") }}
                        </div>
                    </div>
                    <div class="flex flex-col w-1/2 gap-5">
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Signature: </strong>
                            <div>
                                {{ $salarie->nom }} {{ $salarie->prenom }}<br>
                                <i>{{ $salarie->poste }}</i><br>
                                <b>{{ $company->name }}</b>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between items-center border-separate">
                            <strong>Nom: </strong> {{ $salarie->nom }}
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="tabs === 'contract'">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-col">
                        <strong>Nom: </strong> {{ $salarie->nom }}
                    </div>
                </div>
            </div>
            <div x-show="tabs === 'rh'">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-col">
                        <strong>Nom: </strong> {{ $salarie->nom }}
                    </div>
                </div>
            </div>
            <div x-show="tabs === 'planning'">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-col">
                        <strong>Nom: </strong> {{ $salarie->nom }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
