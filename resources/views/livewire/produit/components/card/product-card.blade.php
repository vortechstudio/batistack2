<div class="flex flex-row justify-between items-center mb-5">
    <div class="flex flex-row justify-between items-center">
        <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
            @svg('heroicon-o-shopping-bag', 'w-8 h-8')
        </div>
        <div class="ml-4">
            <div class="text-lg font-bold">{{ $produit->name }}</div>
            <div class="text-sm text-gray-500">{{ $produit->reference }}</div>
        </div>
    </div>
    <div class="flex flex-row justify-between items-center gap-2">
        <x-ui.badge :color="$produit->achat ? 'green' : 'red'" size="md" :text="$produit->achat ? 'En achat' : 'Hors Achat'" />
        <x-ui.badge :color="$produit->vente ? 'green' : 'red'" size="md" :text="$produit->vente ? 'En vente' : 'Hors vente'" />
    </div>
</div>
