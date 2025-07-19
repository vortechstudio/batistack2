<div>
    <iframe
        src="https://docs.google.com/gview?url={{ Storage::disk('public')->url('rh/salarie/'.$contrat->employe->id.'/documents/contrat.pdf') }}&embedded=true"
        class="w-full h-full border-2 border-gray-200 rounded-lg mb-10"
        frameborder="0"
    >
        Votre navigateur ne supporte pas les PDF. Téléchargez le <a href="{{ Storage::disk('public')->url('rh/salarie/'.$contrat->employe->id.'/documents/contrat.pdf') }}">ici</a>.
    </iframe>

    <form wire:submit="signContract">
        <x-mary-card title="Signature de votre contrat de travail" shadow separator class="bg-blue-300">
            <p>La signature de votre contrat de travail rend effectif, vos obligations à compter du jours définie sur votre contrat de travail.</p>
            <p>Un code OTP (Authentification Utilisateur) vous à été envoyer sur l'adresse mail définie lors de votre entretien préalable à l'embauche.</p>
            <p class="mb-5">Veuillez saisir le code OTP dans le champ ci-dessous.</p>
            {{ $this->form }}

            <x-mary-button type="submit" label="Valider" class="btn-primary btn-block mt-10" spinner="signContract" />
        </x-mary-card>
    </form>

</div>
