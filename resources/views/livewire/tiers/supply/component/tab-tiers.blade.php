<div>
    <div class="flex justify-end gap-3 mb-5">
        {{ $this->draftMailAction }}
        {{ $this->editAction }}
        {{ $this->deleteAction }}
    </div>
    <div class="grid grid-cols-2 gap-4">
        <ul class="list bg-base-100 rounded-box shadow-md">

            <li class="list-row">
                <div>
                    <div>Nature du Tiers</div>
                    <div class="badge badge-sm bg-{{ $tiers->nature->color() }}-300 text-white">{{ $tiers->nature->label() }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>Type de tiers</div>
                    <div class="text-xs uppercase font-semibold opacity-60">{{ $tiers->type->label() }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>Code Fournisseur</div>
                    <div class="text-xs uppercase font-semibold opacity-60">{{ $tiers->code_tiers }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>Siren</div>
                    <div class="text-xs uppercase font-semibold opacity-60">{{ $tiers->siren }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>TVA</div>
                    <x-ui.badge
                        :color="$tiers->tva ? 'green' : 'red'"
                        :text="$tiers->tva ? 'Oui' : 'Non'" />
                </div>
            </li>
            @if($tiers->tva)
                <li class="list-row">
                    <div>
                        <div>Numéro de TVA Intracommunautaire</div>
                        <div class="text-xs uppercase font-semibold opacity-60">{{ $tiers->num_tva }}</div>
                    </div>
                </li>
            @endif

        </ul>
        {{ $this->table }}
    </div>
    <x-filament-actions::modals />
</div>
