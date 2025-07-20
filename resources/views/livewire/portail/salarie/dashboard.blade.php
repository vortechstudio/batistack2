<div>
    @if($user->employe->contrat->status->value === 'checked')
        <x-mary-alert class="alert-warning mb-5" icon="o-exclamation-triangle" title="Contrat en attente de signature" description="Votre contrat de travail est en attente de signature, vous avez jusqu'au {{ $user->employe->contrat->signed_start_at->addDays(3)->format('d/m/Y à H:i') }} pour le signer.">
            <x-slot:actions>
                <x-mary-button label="Signer mon contrat" class="btn-success" link="{{ route('portail.salarie.documents.signed', $user->employe->contrat->id) }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
    @empty($user->employe->bank)
        <x-mary-alert class="alert-warning mb-5" icon="o-exclamation-triangle" title="Veuillez renseigner vos informations bancaires" description="Veuillez renseigner vos informations bancaires pour pouvoir recevoir votre salaire.">
            <x-slot:actions>
                <x-mary-button label="Renseigner mes informations bancaires" class="btn-success" link="{{ route('portail.salarie.dashboard') }}" />
            </x-slot:actions>
        </x-mary-alert>
    @endif
    @if($user->employe->info->process->value === 'contract_sign')
        @php
            $now = now();
            $interval = $user->employe->contrat->date_debut->diff($now);
        @endphp
        <div class="flex flex-col bg-gray-100 rounded justify-center items-center p-5 mb-10" wire:poll.60s>
            <div class="grid auto-cols-max grid-flow-col gap-5 text-center">
                <div class="bg-neutral rounded-box text-neutral-content flex flex-col p-2">
                    <span class="countdown font-mono text-5xl">
                    <span style="--value:{{ $interval->d }};" aria-live="polite" aria-label="15">15</span>
                    </span>
                    Jours
                </div>
                <div class="bg-neutral rounded-box text-neutral-content flex flex-col p-2">
                    <span class="countdown font-mono text-5xl">
                    <span style="--value:{{ $interval->h }};" aria-live="polite" aria-label="10">10</span>
                    </span>
                    Heure
                </div>
                <div class="bg-neutral rounded-box text-neutral-content flex flex-col p-2">
                    <span class="countdown font-mono text-5xl">
                    <span style="--value:{{ $interval->m }};" aria-live="polite" aria-label="24">24</span>
                    </span>
                    Min
                </div>

                </div>
        </div>
    @endif
</div>
