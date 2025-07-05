<div>
    <div class="bg-gray-100 rounded-lg p-5 mb-10">
        <div class="flex flex-col">
            <span class="text-2xl">{{ $chantier->tiers->contacts()->first()->civilite }} {{ $chantier->tiers->contacts()->first()->nom }} {{ $chantier->tiers->contacts()->first()->prenom }}</span>
            <span class="text-lg flex flex-row">
                @svg('heroicon-c-map-pin', 'size-[24px] text-gray-800 me-3') {{ $chantier->addresses()->first()->address }} {{ $chantier->addresses()->first()->code_postal }} {{ $chantier->addresses()->first()->ville }}
            </span>
        </div>
        <div class="divider"></div>
        <div class="flex flex-row justify-between gap-4">
            <div class="flex flex-col">
                <span>Facturation</span>
                <progress class="progress progress-error w-90" value="0" max="100"></progress>
                <div class="flex flex-row justify-between items-center">
                    <span class="text-gray-300">0%</span>
                    <span class="text-gray-300">0,00 € / 3 580,00 €</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span>Paiement</span>
                <progress class="progress progress-success w-90" value="0" max="100"></progress>
                <div class="flex flex-row justify-between items-center">
                    <span class="text-gray-300">0%</span>
                    <span class="text-gray-300">0,00 € / 3 580,00 €</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span>Marges</span>
                <progress class="progress progress-info w-90" value="27" max="100"></progress>
                <div class="flex flex-row justify-between items-center">
                    <span class="text-gray-300">26,78 %</span>
                    <span class="text-gray-300">1 985,45 € / 3 580,00 €</span>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-100 rounded-lg mb-10" x-data="{tabs: 'devis'}">
        <div class="flex flex-row justify-between items-center p-5">
            <span class="font-bold">Documents lié</span>
            <div role="tablist" class="tabs">
                <a role="tab" class="tab" @click="tabs = 'devis'" :class="tabs === 'devis' ? 'tab tab-active' : 'tab'">Devis</a>
                <a role="tab" class="tab" @click="tabs = 'facture'" :class="tabs === 'facture' ? 'tab tab-active' : 'tab'">Factures</a>
                <a role="tab" class="tab" @click="tabs = 'avoir'" :class="tabs === 'avoir' ? 'tab tab-active' : 'tab'">Avoir</a>
            </div>
        </div>
        <div class="mt-4">
            <div x-show="tabs === 'devis'" class="p-4 bg-white">
                @livewire('chantier.components.table.document-devis-table', ['chantier' => $chantier])
            </div>
            <div x-show="tabs === 'facture'" class="p-4 bg-white">
                @livewire('chantier.components.table.document-facture-table', ['chantier' => $chantier])
            </div>
        </div>
    </div>
</div>
