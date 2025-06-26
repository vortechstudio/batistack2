<div>
    <div class="flex justify-between items-center p-10 bg-white shadow">
        <div class="flex items-center gap-2">
            @svg('solar-buildings-2-bold-duotone', 'size-24 rounded-full')
            <div>
                <span class="font-bold text-blue-400 text-2xl">{{ $tiers->name }}</span>
                <div class="flex flex-col text-md">
                    <div class="flex items-center mb-1">
                        @svg('solar-map-point-wave-bold-duotone', 'size-8 me-2') {{ $tiers->addresses->first()->address }}, {{ $tiers->addresses->first()->code_postal }} {{ $tiers->addresses->first()->ville }}, {{ $tiers->addresses->first()->pays }}
                    </div>
                    <div class="flex items-center mb-1">
                        @svg('solar-phone-calling-rounded-line-duotone', 'size-8 me-2') {{ $tiers->contacts->first()->tel }}
                    </div>
                    <div class="flex items-center mb-1">
                        @svg('solar-letter-line-duotone', 'size-8 me-2') {{ $tiers->contacts->first()->email }}
                    </div>
                </div>
            </div>
        </div>
        <x-ui.badge
            :color="$tiers->nature->color()"
            :text="$tiers->nature->label()"
            size="xl" />

    </div>
    <div class="bg-white shadow rounded">
        <div class="tabs tabs-border">
            <input type="radio" name="options" class="tab" aria-label="Tiers"/>
            <div class="tab-content border-base-300 bg-base-100 p-10" id="my_tabs_1">
                @livewire('tiers.supply.component.tab-tiers', ['tiers' => $tiers])
            </div>

            <input type="radio" name="options" class="tab" aria-label="Contacts/Adresses" />
            <div class="tab-content border-base-300 bg-base-100 p-10">
                @livewire('tiers.supply.component.tab-contact', ['tiers' => $tiers])
            </div>

            <input type="radio" name="options" class="tab" aria-label="Fournisseur" checked="checked" />
            <div class="tab-content border-base-300 bg-base-100 p-10">
                @livewire('tiers.supply.component.tab-supply', ['tiers' => $tiers])
            </div>

            <input type="radio" name="options" class="tab" aria-label="Produits" />
            <div class="tab-content border-base-300 bg-base-100 p-10">
                Liste des Produits
            </div>

            <input type="radio" name="options" class="tab" aria-label="Mode de Règlements" />
            <div class="tab-content border-base-300 bg-base-100 p-10">
                @livewire('tiers.supply.component.tab-bank', ['tiers' => $tiers])
            </div>
        </div>
    </div>
</div>
