<div>
    <flux:heading size="xl">Paramètre de la société</flux:heading>
    <div class="overflow-hidden rounded-lg bg-white shadow w-3/6 m-auto p-5">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit="create">
                {{ $this->form }}

                <div class="flex justify-end my-3">
                    <flux:button type="submit" variant="primary">Valider</flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
