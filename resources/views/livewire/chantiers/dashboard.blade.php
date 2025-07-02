<div>
    <div class="grid grid-cols-4 gap-4 my-5">
        @livewire('chantiers.widgets.nb-chantiers-stats')
    </div>
    {{ $this->table }}

    <x-filament-actions::modals />

</div>
