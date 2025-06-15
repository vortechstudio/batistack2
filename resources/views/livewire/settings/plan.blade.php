<div>
    <div class="flex justify-between align-center mb-10">
        <div class="text-xl font-bold">Plan Comptable</div>
        {{ $this->createPlanAction }}
    </div>
    {{ $this->table }}

    <x-filament-actions::modals />
</div>
