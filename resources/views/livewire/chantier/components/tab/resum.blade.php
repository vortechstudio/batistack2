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
                <div class="flex flex-row justify-between items-center">
                    <span class="text-gray-300">{{ Number::currency(\App\Models\Commerce\Facture::where('chantiers_id', $chantier->id)->where('status', '!=', 'payer')->sum('amount_ttc'), 'EUR', 'fr') }} / {{ Number::currency(\App\Models\Commerce\Devis::where('chantiers_id', $chantier->id)->where('status', 'accepted')->sum('amount_ttc'), 'EUR', 'fr') }}</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span>Paiement</span>
                <div class="flex flex-row justify-between items-center">
                    <span class="text-gray-300">{{ Number::currency(\App\Models\Commerce\Facture::where('chantiers_id', $chantier->id)->where('status', 'payer')->sum('amount_ttc'), 'EUR', 'fr') }} / {{ Number::currency(\App\Models\Commerce\Facture::where('chantiers_id', $chantier->id)->sum('amount_ttc'), 'EUR', 'fr') }}</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span>Budgetisation</span>
                <progress class="progress progress-info w-90" value="{{ $chantier->ecart_budget_percent }}" max="100"></progress>
                <div class="flex flex-row justify-between items-center">
                    <span class="text-gray-300">{{ $chantier->ecart_budget_percent }} %</span>
                    <span class="text-gray-300">{{ Number::currency($chantier->budget_estime, 'EUR', 'fr') }} / {{ Number::currency($chantier->budget_reel, 'EUR', 'fr') }}</span>
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
