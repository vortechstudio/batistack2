<div>
    <div class="card bg-base-100 card-md shadow-sm">
        <div class="card-body">
            <h2 class="card-title">Configuration des devis clients</h2>
            <form wire:submit="updateSettings">
                {{ $this->editDevisForm }}
                <div class="justify-end card-actions">
                    <button type="submit" class="btn btn-primary mt-5">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
