<div>
    <div class="bg-gray-100 rounded p-5">
        <form wire:submit="transmit">
            {{ $this->form }}

            <div class="flex justify-end">
                <x-mary-button label="Valider" type="submit" class="btn-primary mt-2" spinner="transmit" />
            </div>
        </form>
    </div>
</div>
