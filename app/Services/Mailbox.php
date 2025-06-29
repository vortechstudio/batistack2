<?php

declare(strict_types=1);

namespace App\Services;

use PhpImap\Exceptions\InvalidParameterException;

final class Mailbox
{
    public ?string $err = null;

    private readonly string $mdx;

    private readonly \PhpImap\Mailbox $connect;

    public function __construct(
        public string $type = 'default'
    ) {
        $this->mdx = '{'.config('batistack.imap.host').':'.config('batistack.imap.port').'/imap/ssl}INBOX';
        $this->connect = new \PhpImap\Mailbox(
            $this->mdx,
            config('batistack.imap.'.$this->type.'.username'),
            config('batistack.imap.'.$this->type.'.password'),
        );
        try {
            $this->connect->setConnectionArgs(CL_EXPUNGE);
        } catch (InvalidParameterException $e) {
            $this->err = $e->getMessage();
        }
    }

    public function getFoldersFormat()
    {
        return collect($this->getFolders())
            ->map(function (array $folder) {
                $folder['shortpath'] = str_replace('INBOX.', '', $folder['shortpath']);

                return $folder;
            })->toArray();
    }

    public function getAllMessages()
    {
        return collect($this->connect->searchMailbox('ALL'))
            ->map(fn ($mailId): \PhpImap\IncomingMail => $this->connect->getMail($mailId));
    }

    public function getMessageBody(string $messageId)
    {
        return $this->connect->getMail($messageId)->textHtml;
    }

    public function getMessagesFromFolders(string $folder): array
    {
        $this->connect->switchMailbox($folder);
    }

    private function getFolders(): array
    {
        return $this->connect->getMailboxes('*');
    }
}
