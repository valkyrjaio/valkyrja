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

namespace Valkyrja\Mail\Messages;

use Valkyrja\Mail\Message as Contract;

/**
 * Class Message.
 *
 * @author Melech Mizrachi
 */
class Message implements Contract
{
    /**
     * The from email.
     *
     * @var string
     */
    protected string $fromEmail = '';

    /**
     * The from name.
     *
     * @var string
     */
    protected string $fromName = '';

    /**
     * The recipients.
     *
     * @var array[]
     */
    protected array $recipients = [];

    /**
     * The reply to recipients.
     *
     * @var array[]
     */
    protected array $replyToRecipients = [];

    /**
     * The copy recipients.
     *
     * @var array[]
     */
    protected array $copyRecipients = [];

    /**
     * The blind copy recipients.
     *
     * @var array[]
     */
    protected array $blindCopyRecipients = [];

    /**
     * The attachments.
     *
     * @var array[]
     */
    protected array $attachments = [];

    /**
     * The subject.
     *
     * @var string
     */
    protected string $subject = '';

    /**
     * The body.
     *
     * @var string
     */
    protected string $body = '';

    /**
     * Whether the message body is html.
     *
     * @var bool
     */
    protected bool $isHtml = false;

    /**
     * The plain body.
     *
     * @var string|null
     */
    protected ?string $plainBody = null;

    /**
     * Get the from email.
     *
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * Get the from name.
     *
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * Set the mail's sender information.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function setFrom(string $email, string $name = ''): self
    {
        $this->fromEmail = $email;
        $this->fromName  = $name;

        return $this;
    }

    /**
     * Get the recipients.
     *
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Add a recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addRecipient(string $email, string $name = ''): self
    {
        $this->recipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * Get the reply to recipients.
     *
     * @return array
     */
    public function getReplyToRecipients(): array
    {
        return $this->replyToRecipients;
    }

    /**
     * Add a Reply-To recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addReplyTo(string $email, string $name = ''): self
    {
        $this->replyToRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * Get the copy recipients.
     *
     * @return array
     */
    public function getCopyRecipients(): array
    {
        return $this->copyRecipients;
    }

    /**
     * Add a copy (CC) recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addCopyRecipient(string $email, string $name = ''): self
    {
        $this->copyRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * Get the blind copy recipients.
     *
     * @return array
     */
    public function getBlindCopyRecipients(): array
    {
        return $this->blindCopyRecipients;
    }

    /**
     * Add a blind copy (BCC) recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addBlindCopyRecipient(string $email, string $name = ''): self
    {
        $this->blindCopyRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * Get the attachments.
     *
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * Add an attachment from the filesystem.
     *
     * @param string $path The path
     * @param string $name [optional] The name
     *
     * @return static
     */
    public function addAttachment(string $path, string $name = ''): self
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name,
        ];

        return $this;
    }

    /**
     * Get the subject.
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set the subject.
     *
     * @param string $subject The subject
     *
     * @return static
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set the body of the mail.
     *
     * @param string $body The body
     *
     * @return static
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get whether the message body is html.
     *
     * @return bool
     */
    public function isHtml(): bool
    {
        return $this->isHtml;
    }

    /**
     * Set whether the message body is html.
     *
     * @param bool $isHtml [optional] Whether the message body is html
     *
     * @return static
     */
    public function setIsHtml(bool $isHtml = true): self
    {
        $this->isHtml = $isHtml;

        return $this;
    }

    /**
     * Get the plain body.
     *
     * @return string|null
     */
    public function getPlainBody(): ?string
    {
        return $this->plainBody;
    }

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string|null $plainBody The plain body message
     *
     * @return static
     */
    public function setPlainBody(string $plainBody = null): self
    {
        $this->plainBody = $plainBody;

        return $this;
    }
}
