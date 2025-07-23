<div>
    <div class="bg-gray-100 rounded-md p-5">
        <form wire:submit="updateCompany">
            {{ $this->form }}
            <div class="flex justify-end mt-5">
                <x-mary-button label="Valider" type="submit" class="btn-primary" spinner="updateCompany" />
            </div>
        </form>

    </div>
</div>
