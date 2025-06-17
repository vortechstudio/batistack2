<div class="container flex items-stretch gap-5">
    <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow-sm w-1/2">
        <div class="px-4 py-5 sm:px-6 bg-gray-100">
            <span class="font-bold">Statistiques</span>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <x-mary-chart wire:model="statTiers" class="h-[size:300px]" />
        </div>
        <div class="px-4 py-4 sm:px-6">
            <div class="flex justify-between items-center">
                <span>Nombre total de tiers</span>
                <span>{{ $countClient+$countFournisseur }}</span>
            </div>
        </div>
    </div>
    <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow-sm w-1/2">
        <div class="px-4 py-5 sm:px-6 bg-gray-100">
            <span class="font-bold">Les 3 derniers tiers modifiés</span>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <table class="table">
                <tbody>
                    @foreach($tiers as $tier)
                        <tr>
                            <td>{{ $tier->name }}</td>
                            <td>
                                <span class="badge bg-{{ $tier->nature->color() }}-200">{{ $tier->nature->label() }}</span>
                            </td>
                            <td>{{ $tier->contacts()->first()->tel }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
