<?php

declare(strict_types=1);

test('peut définir le contenu du message')
    ->expect(fn () => (new App\Channels\Messages\WhatsAppMessage)
        ->content('Mon message test')
    )->content->toBe('Mon message test');
