<div>
    <form wire:submit="aggregate">
        <div class="flex flex-col w-3/4 mx-auto">
            @foreach($accounts as $account)
                <div class="my-5 overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="sm:flex">
                            <div class="mb-4 shrink-0 sm:mb-0 sm:mr-4">
                                <img src="{{ $this->banque->bank->logo }}" class="h-32 w-full border border-gray-300 sm:w-32" alt="">
                            </div>
                            <div>
                                <h4 class="text-lg font-bold pb-2">{{ $this->banque->bank->name }}</h4>
                                <div class="flex flex-col">
                                    <div><span class="font-bold">Compte: </span>{{ $account['name'] }}</div>
                                    <div><span class="font-bold">Solde: </span>{{ isset($account['balance']) ? \App\Helpers\Helpers::eur($account['balance']) : 'Inconnue' }}</div>
                                    <div><span class="font-bold">IBAN: </span>{{ isset($account['iban']) ? $account['iban'] : 'Inconnue' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-end">
            <x-mary-button label="Valider" type="submit" class="btn-primary" spinner="save2" />
        </div>
    </form>
</div>
