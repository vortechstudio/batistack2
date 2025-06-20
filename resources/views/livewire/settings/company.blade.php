<div>
    <div class="text-xl font-bold">Paramètre de la société</div>
    <div class="overflow-hidden rounded-lg bg-white shadow w-3/6 m-auto p-5">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit="create">
                {{ $this->form }}

                <div class="flex justify-end my-3">
                    <flux:button type="submit" variant="primary">Valider</flux:button>
                </div>
            </form>
            <div class="my-5 border border-2"></div>
            <form wire:submit="uploadLogo" enctype="multipart/form-data">
                <flux:heading size="lg">Logo de la société</flux:heading>
                <div wire:ignore class="mt-2">
                    <input type="file" wire:model="logo" class="file-input" />
                    <input type="file" wire:model="logo_wide" class="file-input" />
                </div>
                <div class="flex justify-end my-3">
                    <flux:button type="submit" variant="primary">Valider</flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
