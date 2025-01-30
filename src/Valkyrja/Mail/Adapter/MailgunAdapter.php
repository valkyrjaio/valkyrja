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

namespace Valkyrja\Mail\Adapter;

use Mailgun\Mailgun;
use Mailgun\Message\BatchMessage;
use Mailgun\Message\Exceptions\MissingRequiredParameter;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Mail\Adapter\Contract\MailgunAdapter as Contract;
use Valkyrja\Mail\Message\Contract\Message;

use function is_string;

/**
 * Class MailGunAdapter.
 *
 * @author Melech Mizrachi
 */
class MailgunAdapter implements Contract
{
    /**
     * MailgunAdapter constructor.
     *
     * @param Mailgun              $mailgun The mailgun service
     * @param array<string, mixed> $config  The config
     */
    public function __construct(
        protected Mailgun $mailgun,
        protected array $config
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws MissingRequiredParameter
     */
    public function send(Message $message): void
    {
        $domain = $this->config['domain'];

        if (! is_string($domain)) {
            throw new InvalidArgumentException('Domain in config must be a string');
        }

        $mailgunMessage = $this->mailgun->messages()->getBatchMessage($domain);
        $replyTo        = $message->getReplyToRecipients()[0] ?? null;
        $from           = [['email' => $message->getFromEmail(), 'name' => $message->getFromName()]];

        $mailgunMessage->setSubject($message->getSubject());
        $mailgunMessage->setTextBody($message->getBody());

        if ($message->isHtml()) {
            $mailgunMessage->setHtmlBody($message->getBody());
            $mailgunMessage->setTextBody($message->getPlainBody() ?? '');
        }

        $this->setRecipients($mailgunMessage, 'setFromAddress', $from);
        $this->setRecipients($mailgunMessage, 'addCcRecipient', $message->getCopyRecipients());
        $this->setRecipients($mailgunMessage, 'addBccRecipient', $message->getBlindCopyRecipients());
        $this->addAttachments($mailgunMessage, $message->getAttachments());

        if ($replyTo !== null) {
            $this->setRecipients($mailgunMessage, 'setReplyToAddress', [$replyTo]);
        }

        $this->setRecipients($mailgunMessage, 'addToRecipient', $message->getRecipients());

        $mailgunMessage->finalize();
    }

    /**
     * Add recipients to a mailgun batch method by method.
     *
     * @param BatchMessage                                   $mailgunMessage The mailgun batch message
     * @param string                                         $method         The method to call
     * @param array<int, array{email: string, name: string}> $recipients     The recipients
     *
     * @return void
     */
    protected function setRecipients(BatchMessage $mailgunMessage, string $method, array $recipients): void
    {
        foreach ($recipients as $recipient) {
            $mailgunMessage->{$method}($recipient['email'], ['full_name' => $recipient['name']]);
        }
    }

    /**
     * Add attachments to a mailgun batch message.
     *
     * @param BatchMessage                                  $mailgunMessage The mailgun batch message
     * @param array<int, array{path: string, name: string}> $attachments    The attachments
     *
     * @return void
     */
    protected function addAttachments(BatchMessage $mailgunMessage, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $mailgunMessage->addAttachment($attachment['path'], $attachment['name']);
        }
    }
}
