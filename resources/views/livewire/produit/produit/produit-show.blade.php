<div x-data="{tabs: 'produit'}">
    <div class="flex flex-row justify-between items-center mb-10 bg-gray-100 rounded-md p-4">
        <div role="tablist" class="tabs tabs-border">
            <a role="tab" :class="tabs === 'produit' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'produit'">Produit</a>
            <a role="tab" :class="tabs === 'price_vente' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'price_vente'">Prix de vente</a>
            <a role="tab" :class="tabs === 'price_achat' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'price_achat'">Prix d'achat</a>
            <a role="tab" :class="tabs === 'stock' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'stock'">Stocks</a>
            <a role="tab" :class="tabs === 'referencial' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'referencial'">Objets Référents</a>
            <a role="tab" :class="tabs === 'statistiques' ? 'tab tab-active' : 'tab'" x-on:click="tabs = 'statistiques'">Statistiques</a>
        </div>
        <!--Action-->
    </div>
    <div class="bg-gray-100 rounded-md p-4">
        <div x-show="tabs === 'produit'">
            @livewire('produit.components.card.product-card', ['produit' => $produit])
            <div class="flex flex-row justify-between gap-5">
                <div class="flex flex-col w-1/2">
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Numéro de série</div>
                        <div class="text-sm">{{ $produit->serial_number }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Code comptable</div>
                        <div class="text-sm">{{ $produit->codeComptableVente->code }} - {{ $produit->codeComptableVente->account }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Description</div>
                        <div class="text-sm">{{ $produit->description }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Catégorie</div>
                        <div class="text-sm">{{ $produit->category->name }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Entrepot par default</div>
                        <div class="text-sm">{{ $produit->entrepot->name }}</div>
                    </div>
                </div>
                <div class="flex flex-col w-1/2">
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Poids</div>
                        <div class="text-sm">{{ $produit->poids_value }} {{ $produit->poids_unite->value }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Longueur x Largeur x Hauteur</div>
                        <div class="text-sm">{{ $produit->longueur }} X {{ $produit->largeur }} X {{ $produit->hauteur }} {{ $produit->llh_unite->value }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Date de mise à jour</div>
                        <div class="text-sm">{{ $produit->updated_at->format('d/m/Y H:i:s') }} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
