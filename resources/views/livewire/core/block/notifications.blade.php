<flux:dropdown wire:poll.visible>
    @if(count($notifications) > 0)
        <flux:button wire:click="readNotification" icon:trailing="bell"><span class="status status-error animate-ping"></span></flux:button>
    @else
        <flux:button href="{{ route('notifications') }}" icon:trailing="bell"></flux:button>
    @endif

    @if(count($notifications) > 0)
            <flux:menu class="w-1/4">
                @foreach($notifications as $notification)
                    <div class="flex justify-between items-center mx-2 my-4 p-2 bg-{{ $notification['data']['type'] }}-50 rounded">
                        <div class="flex items-center">
                            <figure class="flex justify-center items-center border border-{{ $notification['data']['type'] }}-500 rounded-md w-12! h-12! bg-gray- me-3">
                                @svg($this->getIconNotification($notification->id), 'text-'.$notification['data']['type'].'-500')
                            </figure>
                            <div class="">
                                <span class="font-bold text-lg">{{ $notification['data']['title'] }}</span><br>
                                <p>{{ $notification['data']['description'] }}</p>
                            </div>
                        </div>
                        <flux:badge color="lime">{{ $notification->created_at->shortRelativeDiffForHumans() }}</flux:badge>
                    </div>
                    <flux:separator></flux:separator>
                @endforeach
            </flux:menu>
    @endif
</flux:dropdown>
