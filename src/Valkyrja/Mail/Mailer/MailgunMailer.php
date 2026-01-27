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

use Mailgun\Mailgun;
use Mailgun\Message\BatchMessage;
use Mailgun\Message\Exceptions\MissingRequiredParameter;
use Override;
use Psr\Http\Client\ClientExceptionInterface;
use Valkyrja\Mail\Data\Contract\AttachmentContract;
use Valkyrja\Mail\Data\Contract\MessageContract;
use Valkyrja\Mail\Data\Contract\RecipientContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;

class MailgunMailer implements MailerContract
{
    /**
     * @param non-empty-string $domain The domain
     */
    public function __construct(
        protected Mailgun $mailgun,
        protected string $domain,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws MissingRequiredParameter
     * @throws ClientExceptionInterface
     */
    #[Override]
    public function send(MessageContract $message): void
    {
        $domain = $this->domain;

        $mailgunMessage = $this->mailgun->messages()->getBatchMessage($domain);
        $replyTo        = $message->getReplyToRecipients()[0] ?? null;

        $mailgunMessage->setSubject($message->getSubject());
        $mailgunMessage->setTextBody($message->getBody());

        if ($message->isHtml()) {
            $mailgunMessage->setHtmlBody($message->getBody());
            $mailgunMessage->setTextBody($message->getPlainBody() ?? '');
        }

        $this->setRecipients([$mailgunMessage, 'setFromAddress'], [$message->getFrom()]);
        $this->setRecipients([$mailgunMessage, 'addCcRecipient'], $message->getCopyRecipients());
        $this->setRecipients([$mailgunMessage, 'addBccRecipient'], $message->getBlindCopyRecipients());
        $this->addAttachments($mailgunMessage, $message->getAttachments());

        if ($replyTo !== null) {
            $this->setRecipients([$mailgunMessage, 'setReplyToAddress'], [$replyTo]);
        }

        $this->setRecipients([$mailgunMessage, 'addToRecipient'], $message->getRecipients());

        $mailgunMessage->finalize();
    }

    /**
     * Add recipients to a mailgun batch method by method.
     *
     * @param callable                      $callable   The callable to add the recipient to
     * @param array<int, RecipientContract> $recipients The recipients
     */
    protected function setRecipients(callable $callable, array $recipients): void
    {
        foreach ($recipients as $recipient) {
            $nameArray = [];

            if ($recipient->getName() !== null) {
                $nameArray = ['full_name' => $recipient->getName()];
            }

            $callable($recipient->getEmail(), $nameArray);
        }
    }

    /**
     * Add attachments to a mailgun batch message.
     *
     * @param BatchMessage                   $mailgunMessage The mailgun batch message
     * @param array<int, AttachmentContract> $attachments    The attachments
     */
    protected function addAttachments(BatchMessage $mailgunMessage, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $mailgunMessage->addAttachment($attachment->getPath(), $attachment->getName() ?? '');
        }
    }
}
