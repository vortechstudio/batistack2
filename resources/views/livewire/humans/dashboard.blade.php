<div>
    <div class="grid grid-cols-2 gap-5">
        <x-mary-card class="bg-gray-100 mb-10" title="Congés" shadow separator>
            Solde des congés en jours:
        </x-mary-card>
        <div>
            <x-mary-card class="bg-gray-100 mb-10" title="Les 5 dernières demandes de congés" shadow separator>
                Liste
            </x-mary-card>
            <x-mary-card class="bg-gray-100 mb-10" title="Les 5 dernières notes de frais modifiées" shadow separator>
                Liste
            </x-mary-card>
        </div>
    </div>
    <div class="flex align-top gap-5 mb-10">
        <div class="w-1/2">
            @livewire('humans.components.widgets.stats-frais')
        </div>
        <div class="w-1/2">
            @livewire('humans.components.tables.table-frais-limit')
        </div>
    </div>
</div>
