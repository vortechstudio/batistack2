<div>
    @if(Auth::user()->employe->contrat->status->value === 'checked')
        <x-mary-alert class="alert-warning mb-10" icon="o-exclamation-triangle" title="Contrat en attente de signature" description="Votre contrat de travail est en attente de signature, vous avez jusqu'au {{ Auth::user()->employe->contrat->signed_start_at->addDays(3)->format('d/m/Y à H:i') }} pour le signer.">
            <x-slot:actions>
                <x-mary-button label="Signer mon contrat" class="btn-success" link="{{ route('portail.salarie.documents.signed', Auth::user()->employe->contrat->id) }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
    <div class="flex content-stretch items-start align-top" x-data="{ged_item: 'all'}">
        <div class="w-1/5 rounded-tl-md rounded-bl-md bg-gray-100 p-5">
            <x-mary-menu>
                <x-mary-menu-item title="/" wire:click="updateGedItem('all')" x-active="ged_item === 'all'" />
                <x-mary-menu-sub title="Documents">
                    <x-mary-menu-item title="RH" wire:click="updateGedItem('rh')" x-active="ged_item === 'rh'" />
                    <x-mary-menu-item title="Fiche de Paie" wire:click="updateGedItem('paie')" x-active="ged_item === 'paie'" />
                </x-mary-menu-sub>
            </x-mary-menu>
        </div>
        <div class="w-4/5 p-5">
            @if($ged_item === 'all')
                <livewire:portail.salarie.documents.all />
            @elseif($ged_item === 'rh')
                <livewire:portail.salarie.documents.r-h />
            @elseif($ged_item === 'paie')
                <livewire:portail.salarie.documents.paie />
            @endif
        </div>
    </div>
</div>
