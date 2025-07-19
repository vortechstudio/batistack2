<div>
    @if($user->employe->contrat->status->value === 'checked')
        <x-mary-alert class="alert-warning" icon="o-exclamation-triangle" title="Contrat en attente de signature" description="Votre contrat de travail est en attente de signature, vous avez jusqu'au {{ $user->employe->contrat->signed_start_at->addDays(3)->format('d/m/Y à H:i') }} pour le signer.">
            <x-slot:actions>
                <x-mary-button label="Signer mon contrat" class="btn-success" link="{{ route('unisharp.lfm.show') }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
</div>
