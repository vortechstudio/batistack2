<?php

namespace App\Channels\Messages;

class WhatsAppMessage
{
    public string $content;

    public function content(string $content)
    {
        $this->content = $content;
        return $this;
    }
}
