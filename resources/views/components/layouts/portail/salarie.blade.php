<x-layouts.portail.sidebar-salarie :title="$title ?? null">
    <flux:main>
        {{ $slot }}
        <x-mary-toast />
    </flux:main>
</x-layouts.portail.sidebar-salarie>
