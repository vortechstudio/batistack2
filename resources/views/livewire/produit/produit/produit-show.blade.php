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
            <div class="flex flex-row justify-end gap-2 mb-10">
                {{ $this->editAction }}
            </div>
            <div class="flex flex-row justify-between gap-5">
                <div class="flex flex-col w-1/2">
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Numéro de série</div>
                        <div class="text-sm">{{ $produit->serial_number }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Code comptable</div>
                        <div class="text-sm">{{ optional($produit->codeComptableVente)->code }} - {{ optional($produit->codeComptableVente)->account }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Description</div>
                        <div class="text-sm">{!! Purifier::clean($produit->description, [
                            'HTML.Allowed' => 'p,br,strong,em,u,ol,ul,li,h1,h2,h3,h4,h5,h6',
                            'HTML.ForbiddenElements' => 'script,style,iframe,object,embed,form,input,button',
                            'Attr.AllowedFrameTargets' => [],
                            'HTML.SafeIframe' => false,
                            'URI.DisableExternalResources' => true,
                        ]) !!}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Catégorie</div>
                        <div class="text-sm">{{ optional($produit->category)->name }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Entrepot par default</div>
                        <div class="text-sm">{{ optional($produit->entrepot)->name }}</div>
                    </div>
                </div>
                <div class="flex flex-col w-1/2">
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Poids</div>
                        <div class="text-sm">{{ $produit->poids_value }} {{ optional($produit->poids_unite)->value }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Longueur x Largeur x Hauteur</div>
                        <div class="text-sm">{{ $produit->longueur }} X {{ $produit->largeur }} X {{ $produit->hauteur }} {{ optional($produit->llh_unite)->value }}</div>
                    </div>
                    <div class="flex flex-row justify-between items-center border-separate border-b mb-2">
                        <div class="text-lg font-bold">Date de mise à jour</div>
                        <div class="text-sm">{{ $produit->updated_at->format('d/m/Y H:i:s') }} </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prix de vente tab content -->
        <div x-show="tabs === 'price_vente'" class="flex items-center justify-center h-64">
            <div class="text-center">
                <div class="text-gray-500 text-lg mb-2">💰</div>
                <div class="text-gray-600 font-medium">Contenu à venir</div>
                <div class="text-gray-500 text-sm">Les informations de prix de vente seront affichées ici</div>
            </div>
        </div>

        <!-- Prix d'achat tab content -->
        <div x-show="tabs === 'price_achat'" class="flex items-center justify-center h-64">
            <div class="text-center">
                <div class="text-gray-500 text-lg mb-2">🛒</div>
                <div class="text-gray-600 font-medium">Contenu à venir</div>
                <div class="text-gray-500 text-sm">Les informations de prix d'achat seront affichées ici</div>
            </div>
        </div>

        <!-- Stock tab content -->
        <div x-show="tabs === 'stock'" class="flex items-center justify-center h-64">
            <div class="text-center">
                <div class="text-gray-500 text-lg mb-2">📦</div>
                <div class="text-gray-600 font-medium">Contenu à venir</div>
                <div class="text-gray-500 text-sm">Les informations de stock seront affichées ici</div>
            </div>
        </div>

        <!-- Objets Référents tab content -->
        <div x-show="tabs === 'referencial'" class="flex items-center justify-center h-64">
            <div class="text-center">
                <div class="text-gray-500 text-lg mb-2">🔗</div>
                <div class="text-gray-600 font-medium">Contenu à venir</div>
                <div class="text-gray-500 text-sm">Les objets référents seront affichés ici</div>
            </div>
        </div>

        <!-- Statistiques tab content -->
        <div x-show="tabs === 'statistiques'" class="flex items-center justify-center h-64">
            <div class="text-center">
                <div class="text-gray-500 text-lg mb-2">📊</div>
                <div class="text-gray-600 font-medium">Contenu à venir</div>
                <div class="text-gray-500 text-sm">Les statistiques seront affichées ici</div>
            </div>
        </div>
    </div>
    <x-filament-actions::modals />
</div>
