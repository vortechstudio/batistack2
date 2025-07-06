<div>
    <div class="flex flex-row justify-end gap-5 mb-5">
        {{ $this->newAchatAction }}
        {{ $this->newTimesheetAction }}
    </div>
    <div class="hero bg-base-200 min-h-3/12 mb-10">
        <div class="hero-content flex-col justify-start lg:flex-row lg:justify-start">
            <x-mary-chart wire:model="chartRentability" />
            <div>
                <div class="flex flex-col mb-5">
                    <span class="text-2xl font-bold">{{ Number::currency($this->chantier->budget_reel, 'EUR', 'fr') }}</span>
                    <span class="text-sm italic text-gray-500">Montant des travaux</span>
                </div>
                <div class="flex flex-row items-center gap-10">
                    <div class="flex flex-col">
                        <span class="text-xl font-bold">{{ Number::currency(2500, 'EUR', 'fr') }}</span>
                        <span class="text-sm italic text-gray-500"><div aria-label="status" class="status status-md bg-cyan-400 me-2"></div> Main d'oeuvre</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold">{{ Number::currency($this->chantier->budget_estime, 'EUR', 'fr') }}</span>
                        <span class="text-sm italic text-gray-500"><div aria-label="status" class="status status-md bg-teal-400 me-2"></div> Achats</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold">{{ Number::currency($this->chantier->marge_chantier, 'EUR', 'fr') }}</span>
                        <span class="text-sm italic text-gray-500"><div aria-label="status" class="status status-md bg-indigo-400 me-2"></div>Marges</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-mary-card title="Achats" shadow separator class="mb-5">
        @livewire('chantier.components.table.table-achats', ['chantier' => $chantier])
    </x-mary-card>
    <x-mary-card title="Ressources" shadow separator class="mb-5">
        @livewire('chantier.components.table.table-achats', ['chantier' => $chantier])
    </x-mary-card>
    <x-filament-actions::modals />
</div>
