<div>
    <div class="flex flex-col bg-gray-100 rounded p-5 mb-10">
        <div class="flex flex-row justify-between items-center">
            <x-mary-avatar :image="$salarie->avatar" class="!w-22">
                <x-slot:title class="text-3xl !font-bold pl-2">
                    {{ $salarie->civility }} {{ $salarie->nom }} {{ $salarie->prenom }}
                </x-slot:title>
                <x-slot:subtitle class="flex items-center mt-2 pl-2 text-sm">
                    @svg('heroicon-o-map-pin', ['class' => 'w-5 h-5 text-gray-300']) {{ $salarie->full_address }}
                </x-slot:subtitle>
            </x-mary-avatar>
            <div class="flex flex-col gap-2">
                <x-ui.badge :color="$salarie->status->color()" size="md" :text="$salarie->status->label()" />
                <x-ui.badge :color="$salarie->info->process->color()" size="md" :text="$salarie->info->process->label()" />
            </div>
        </div>
    </div>
    @if($salarie->info->process->value === 'creating')
        <x-mary-alert
            title="Salarié en cours de création"
            description="Veillez à récupérer les éléments nécessaire à la DPAE et au dossier du nouveau salariés (CNI, Carte BTP, Carte Vital, Rib)"
            icon="s-exclamation-triangle"
            class="alert-info">
            <x-slot:actions>
                <x-mary-button label="Transmettre les documents" link="{{ route('humans.salaries.transmission', $salarie->id) }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
</div>
