<div class="container flex items-center gap-5">
    <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow-sm w-1/2">
        <div class="px-4 py-5 sm:px-6">
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
</div>
