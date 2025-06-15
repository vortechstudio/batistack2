<div class="container">
    {{ $this->deleteAction }}
    <ul class="list bg-base-100 rounded-box shadow-md" wire:poll.5s>

        <li class="p-4 pb-2 text-xs opacity-60 tracking-wide">Mes Notifications ({{ count($notifications) }})</li>

        @foreach($notifications as $notification)
            <li class="list-row items-center">
                <div class="bg-{{ $notification['data']['type'] }}-50 rounded">
                    @svg($notification['data']['icon'], 'size-10 rounded-box text-'.$notification['data']['type'].'-500')
                </div>
                <div>
                    <div>{{ $notification['data']['title'] }}</div>
                    <div class="text-xs uppercase font-semibold opacity-60">
                        {{ $notification['data']['description'] }}
                    </div>
                </div>
                <div class="badge bg-{{ $notification['data']['type'] }}-200">{{ $notification->created_at->shortRelativeDiffForHumans() }}</div>
                <button class="btn btn-square btn-ghost" wire:click="deleteNotif({{ $notification->id }})">
                    @svg('heroicon-o-trash', 'size-[1.2em] text-red-500')
                </button>
            </li>
        @endforeach

    </ul>
    <x-filament-actions::modals />
</div>
