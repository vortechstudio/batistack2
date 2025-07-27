@component('mail.layouts.professional', [
    'subject' => $subject ?? 'Message important',
    'greeting' => $greeting ?? null,
    'actionText' => $actionText ?? null,
    'actionUrl' => $actionUrl ?? null,
    'additionalInfo' => $additionalInfo ?? null
])

{!! $content !!}

@endcomponent
