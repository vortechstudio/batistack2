<div>
    <div class="card bg-base-100 card-md shadow-sm mb-10">
        <div class="card-body">
            <h2 class="card-title">Configuration application</h2>
            <form wire:submit="update">
                {{ $this->form }}
                <div class="justify-end card-actions">
                    <button type="submit" class="btn btn-primary mt-5">Valider</button>
                </div>
            </form>
        </div>
    </div>

</div>
