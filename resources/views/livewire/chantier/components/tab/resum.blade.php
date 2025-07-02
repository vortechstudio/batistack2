<div>
    <div class="bg-gray-100 rounded-lg p-5 mb-10">
        <div class="flex flex-col">
            <span class="text-2xl">{{ $chantier->tiers->contacts()->first()->civilite }} {{ $chantier->tiers->contacts()->first()->nom }} {{ $chantier->tiers->contacts()->first()->prenom }}</span>
            <span class="text-lg flex flex-row">
                @svg('heroicon-c-map-pin', 'size-[24px] text-gray-800 me-3') {{ $chantier->addresses()->first()->address }} {{ $chantier->addresses()->first()->code_postal }} {{ $chantier->addresses()->first()->ville }}
            </span>
        </div>
        <div class="border-separate"></div>
        <div class="grid grid-row-3 gap-4">
            <div class="flex flex-col">
                <span>Facturation</span>
                <progress class="progress w-56" value="10" max="100"></progress>
            </div>
        </div>
    </div>
</div>
