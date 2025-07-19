<div>
    @if(Auth::user()->employe->contrat->status->value === 'checked')
        <x-mary-alert class="alert-warning mb-10" icon="o-exclamation-triangle" title="Contrat en attente de signature" description="Votre contrat de travail est en attente de signature, vous avez jusqu'au {{ Auth::user()->employe->contrat->signed_start_at->addDays(3)->format('d/m/Y à H:i') }} pour le signer.">
            <x-slot:actions>
                <x-mary-button label="Signer mon contrat" class="btn-success" link="{{ route('portail.salarie.documents.signed', Auth::user()->employe->contrat->id) }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
    <iframe src="/filemanager" style="width: 100%; height: 100%; overflow: hidden; border: none;"></iframe>
</div>
