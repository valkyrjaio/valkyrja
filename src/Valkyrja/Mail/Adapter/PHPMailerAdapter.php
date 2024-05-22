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

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Valkyrja\Mail\Adapter\Contract\PHPMailerAdapter as Contract;
use Valkyrja\Mail\Message\Contract\Message;

/**
 * Class PHPMailerAdapter.
 *
 * @author Melech Mizrachi
 */
class PHPMailerAdapter implements Contract
{
    /**
     * The PHP Mailer.
     *
     * @var PHPMailer
     */
    protected PHPMailer $phpMailer;

    /**
     * PHPMailerAdapter constructor.
     *
     * @param PHPMailer $phpMailer
     */
    public function __construct(PHPMailer $phpMailer)
    {
        $this->phpMailer = $phpMailer;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function send(Message $message): void
    {
        $this->phpMailer->setFrom($message->getFromEmail(), $message->getFromName());

        $this->addRecipients('addAddress', $message->getRecipients());
        $this->addRecipients('addReplyTo', $message->getReplyToRecipients());
        $this->addRecipients('addCC', $message->getCopyRecipients());
        $this->addRecipients('addBCC', $message->getBlindCopyRecipients());
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
     * @param string $method     The phpMailer method to call
     * @param array  $recipients The recipients
     *
     * @return void
     */
    protected function addRecipients(string $method, array $recipients): void
    {
        foreach ($recipients as $recipient) {
            $this->phpMailer->{$method}($recipient['email'], $recipient['name']);
        }
    }

    /**
     * Add attachments to PHP Mailer.
     *
     * @param array $attachments The attachments
     *
     * @throws Exception
     *
     * @return void
     */
    protected function addAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $this->phpMailer->addAttachment($attachment['path'], $attachment['name']);
        }
    }

    /**
     * Add plain body to PHP Mailer.
     *
     * @param string|null $plainBody
     *
     * @return void
     */
    protected function addPlainBody(string|null $plainBody = null): void
    {
        if ($plainBody !== null) {
            $this->phpMailer->AltBody = $plainBody;
        }
    }
}
