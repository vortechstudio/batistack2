<div class="grid grid-cols-2 gap-5 items-stretch">
    <div class="flex flex-col bg-white rounded-lg border-2 shadow-sm p-5">
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Nature du tiers</div>
            <div>{{ $tiers->nature->label() }}</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Code Fournisseur</div>
            <div>{{ $tiers->code_tiers }}</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Code comptable général</div>
            <div>{{ $supply->comptaGen->code }} - {{ $supply->comptaGen->account }}</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Code comptable Fournisseur</div>
            <div>{{ $supply->comptaFournisseur->code }} - {{ $supply->comptaFournisseur->account }}</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Assujesti à la TVA</div>
            <x-ui.badge :color="$supply->tva ? 'green' : 'red'" :text="$supply->tva ? 'Oui' : 'Non'" />
        </div>
        @if($supply->tva)
            <div class="flex justify-between items-center border-b-2 py-2">
                <div class="font-bold me-10">Numéro de TVA</div>
                <div>{{ $supply->num_tva }}</div>
            </div>
        @endif
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Condition de règlement</div>
            <div>{{ $supply->condition->name }}</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Mode de règlement</div>
            <div>{{ $supply->reglement->name }}</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Remise Relative</div>
            <div>{{ $supply->rem_relative }} %</div>
        </div>
        <div class="flex justify-between items-center border-b-2 py-2">
            <div class="font-bold me-10">Remise Fixe</div>
            <div>{{ $supply->rem_fixe }} %</div>
        </div>
    </div>
    <div class="bg-white rounded-lg border-2 shadow-sm p-5">
        <div class="grid grid-cols-4 gap-5 mb-10">
            <x-mary-stat
                title="Commandes"
                value="0,00 €"
                icon="o-shopping-cart"
                color="text-primary" />

            <x-mary-stat
                title="Facture"
                value="0,00 €"
                icon="o-document-currency-euro"
                color="text-success" />

            <x-mary-stat
                title="Encours"
                value="0,00 €"
                icon="o-currency-euro"
                color="text-red-500" />

            <x-mary-stat
                title="Facture Impayé"
                value="0,00 €"
                icon="o-currency-euro"
                color="text-red-500" />
        </div>
        <!-- TODO: Tableau de Produits du fournisseur -->
        <!-- TODO: Tableau des factures fournisseur -->
        <div class="grid grid-cols-3 gap-5">
            {{ $this->editAction }}
            <x-mary-button
                link="/"
                class="btn-primary"
                label="Créer une commande" />

            <x-mary-button
                link="/"
                class="btn-primary"
                label="Créer une facture" />

            <x-mary-button
                link="/"
                class="btn-primary"
                label="Créer un avoir" />
        </div>
    </div>

    <x-filament-actions::modals />

</div>
