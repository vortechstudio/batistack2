<?php

declare(strict_types=1);

namespace App\Channels\Messages;

final class WhatsAppMessage
{
    public string $content;

    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }
}
