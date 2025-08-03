<div>
    <div class="flex align-top gap-5 mb-10">
        <div class="w-1/2">
            @livewire('produit.components.widgets.statistique-chart')
        </div>
        <div class="w-1/2 flex flex-col">
            @livewire('produit.components.widgets.dashboard-stat-overview')
        </div>
    </div>
    <div class="flex align-top gap-5 mb-10">
        <div class="w-1/2">
            @livewire('produit.components.widgets.dashboard-table-produit')
        </div>
        <div class="w-1/2">
            @livewire('produit.components.widgets.dashboard-table-service')
        </div>
    </div>
</div>
