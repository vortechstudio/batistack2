<div>
    <div class="flex justify-between align-center mb-10">
        <div class="text-xl font-bold">Création d'un compte</div>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow w-5/6 m-auto p-5">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit="createPlan">
                {{ $this->form }}

                <div class="flex justify-end my-3">
                    <flux:button type="submit" variant="primary">Valider</flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
