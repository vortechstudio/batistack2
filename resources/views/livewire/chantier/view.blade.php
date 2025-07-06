<div>
    <span class="text-2xl font-bold mb-5">Chantier - {{ $chantier->tiers->name }} - {{ $chantier->libelle }}</span>
    <div class="tabs tabs-border">
        <input type="radio" name="my_tabs_2" class="tab" aria-label="Résumé"  />
        <div class="tab-content border-base-300 bg-base-100 p-10">
            @livewire('chantier.components.tab.resum', ['chantier' => $chantier])
        </div>

        <input type="radio" name="my_tabs_2" class="tab" aria-label="Rentabilité" checked="checked" />
        <div class="tab-content border-base-300 bg-base-100 p-10">
            @livewire('chantier.components.tab.rentability', ['chantier' => $chantier])
        </div>

        <input type="radio" name="my_tabs_2" class="tab" aria-label="Galerie" />
        <div class="tab-content border-base-300 bg-base-100 p-10">Tab content 3</div>

        <input type="radio" name="my_tabs_2" class="tab" aria-label="Equipe" />
        <div class="tab-content border-base-300 bg-base-100 p-10">Tab content 3</div>

        <input type="radio" name="my_tabs_2" class="tab" aria-label="Carte" />
        <div class="tab-content border-base-300 bg-base-100 p-10">Tab content 3</div>
    </div>
</div>
