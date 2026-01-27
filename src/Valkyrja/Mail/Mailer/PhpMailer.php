<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Mail\Mailer;

use Override;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerClient;
use Valkyrja\Mail\Data\Contract\AttachmentContract;
use Valkyrja\Mail\Data\Contract\MessageContract;
use Valkyrja\Mail\Data\Contract\RecipientContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;

class PhpMailer implements MailerContract
{
    public function __construct(
        protected PHPMailerClient $phpMailer
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    #[Override]
    public function send(MessageContract $message): void
    {
        $this->phpMailer->setFrom($message->getFrom()->getEmail(), $message->getFrom()->getName() ?? '');

        $this->addRecipients([$this->phpMailer, 'addAddress'], $message->getRecipients());
        $this->addRecipients([$this->phpMailer, 'addReplyTo'], $message->getReplyToRecipients());
        $this->addRecipients([$this->phpMailer, 'addCC'], $message->getCopyRecipients());
        $this->addRecipients([$this->phpMailer, 'addBCC'], $message->getBlindCopyRecipients());
        $this->addAttachments($message->getAttachments());
        $this->addPlainBody($message->getPlainBody());

        $this->phpMailer->Subject = $message->getSubject();
        $this->phpMailer->Body    = $message->getBody();
        $this->phpMailer->isHTML($message->isHtml());

        $this->phpMailer->send();
    }

    /**
     * Add recipients to PHP Mailer by method.
     *
     * @param callable                      $callable   The callable to add the recipient to
     * @param array<int, RecipientContract> $recipients The recipients
     */
    protected function addRecipients(callable $callable, array $recipients): void
    {
        foreach ($recipients as $recipient) {
            $callable($recipient->getEmail(), $recipient->getName() ?? '');
        }
    }

    /**
     * Add attachments to PHP Mailer.
     *
     * @param array<int, AttachmentContract> $attachments The attachments
     *
     * @throws Exception
     */
    protected function addAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $this->phpMailer->addAttachment($attachment->getPath(), $attachment->getName() ?? '');
        }
    }

    /**
     * Add plain body to PHP Mailer.
     */
    protected function addPlainBody(string|null $plainBody = null): void
    {
        if ($plainBody !== null) {
            $this->phpMailer->AltBody = $plainBody;
        }
    }
}
