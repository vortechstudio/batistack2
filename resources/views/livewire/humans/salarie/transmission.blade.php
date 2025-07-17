<div>
    @if($this->salarie->info->process->value === 'transmitting')
    <div class="bg-gray-100 rounded p-5 mb-10">
        <form wire:submit="transmit">
            {{ $this->transmitForm }}

            <div class="flex justify-end">
                <x-mary-button label="Valider" type="submit" class="btn-primary mt-2" spinner="transmit" />
            </div>
        </form>
    </div>
    @endif

    @if($this->salarie->info->process->value === 'validating')
        <div class="bg-gray-100 rounded p-5 mb-10">
            <div class="grid grid-cols-2 gap-5 mb-10">
                    <img class="w-[250px]" src="{{ Storage::disk('public')->url('rh/salarie/'.$this->salarie->id.'/documents/cni-recto.jpg') }}" />
                    <img class="w-[250px]" src="{{ Storage::disk('public')->url('rh/salarie/'.$this->salarie->id.'/documents/carte-vital.jpg') }}" />
            </div>
            <form wire:submit="validating">
                {{ $this->validatingForm }}

                <div class="flex justify-end">
                    <x-mary-button label="Valider" type="submit" class="btn-primary mt-2" spinner="validating" />
                </div>
            </form>
        </div>
    @endif

    @if($this->salarie->info->process->value === 'dpae')
        <div class="bg-gray-100 rounded p-5 mb-10">
            <form wire:submit="sending">
                <x-mary-alert title="DPAE Généré" class="alert-info" icon="o-exclamation-triangle" description="La DPAE à été générer et est pret à l'envoie à votre expert RH, cliquez sur le bouton pour démarrer l'envoie.">
                    {{ $this->sendingForm }}
                    <x-slot:actions>
                        <x-mary-button type="submit" class="btn-primary" label="Transmettre" spinner="sending" />
                    </x-slot:actions>
                </x-mary-alert>
            </form>
        </div>
    @endif

    @if($this->salarie->info->process->value === 'sending_exp')
        <div class="bg-gray-100 rounded p-5 mb-10">
            <form wire:submit="sendingContract">
                {{ $this->sendingContractForm }}
                <div class="flex justify-end">
                    <x-mary-button type="submit" class="btn-primary" label="Transmettre" spinner="sendingContract" />
                </div>
            </form>
        </div>
    @endif
</div>
