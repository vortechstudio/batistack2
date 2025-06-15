<div>
    <div class="flex justify-between align-center mb-10">
        <div class="text-xl font-bold">Banques</div>
        {{ $this->createBankAction }}
    </div>
    <x-mary-alert
        class="alert-info mb-10"
        icon="o-information-circle">
        <span class="text-md font-bold">Information sur l'import de mouvements bancaires</span><br>
        L'import de mouvement bancaire ce fais 3 fois par jours: 6:00, 13:00 et 17:00.
        Si un problème survient lors de l'importation, veuillez contacter le service technique de batistack.
    </x-mary-alert>
    @if(\App\Models\Core\CompanyBank::count() == 0)

    @else
        @foreach($companyBanks as $bank)
            <div class="flex flex-row align-middle items-center justify-between mb-10">
                <div class="flex items-center">
                    <img src="{{ $bank->bank->logo }}" class="w-32 h-auto rounded" alt="">
                    <div class="">
                        <span class="font-bold text-xl">{{ $bank->bank->name }} {{ !empty($bank->bank->group_name) ? "(".$bank->bank->group_name.")" : '' }}</span><br>
                        <span class=""><strong>Dernière Mise à jour:</strong> {{ \Carbon\Carbon::createFromTimestamp($bank->last_refreshed_at)->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
                <div class="badge bg-{{ $bank->bank->status_aggregate->color() }} text-white badge-lg">{{ $bank->bank->status_aggregate->label() }}</div>
            </div>
            <div class="grid grid-cols-4 gap-3">
                @foreach($bank->accounts as $account)
                    <div class="card w-96 bg-base-100 card-md shadow-sm">
                        <div class="card-body">
                            <div class="flex justify-between mb-20">
                                <div>
                                    <h2 class="card-title">{{ $account->name }}</h2>
                                    <p><strong>IBAN:</strong> {{ $account->iban }}</p>
                                </div>

                                <a wire:poll.2s wire:click="refreshMouvement({{ $bank->id }})" class="btn btn-sm btn-circle tooltip" data-tip="Dernière mise à jour de mouvement {{ $account->updated_at->diffForHumans() }}" >
                                    <x-fas-arrows-spin />
                                </a>
                            </div>
                            <span class="font-bold text-2xl text-{{ $account->balance > 0 ? 'success' : 'danger' }}">{{ $account->balance > 0 ? '+ '.\App\Helpers\Helpers::eur($account->balance) : \App\Helpers\Helpers::eur($account->balance) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

        @endforeach
    @endif
</div>
