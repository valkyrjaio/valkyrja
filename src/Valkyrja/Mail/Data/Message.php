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

namespace Valkyrja\Mail\Data;

use Override;
use Valkyrja\Mail\Data\Contract\MessageContract;

class Message implements MessageContract
{
    /**
     * The recipients.
     *
     * @var array<int, array{email: string, name: string}>
     */
    protected array $recipients = [];

    /**
     * The reply to recipients.
     *
     * @var array<int, array{email: string, name: string}>
     */
    protected array $replyToRecipients = [];

    /**
     * The copy recipients.
     *
     * @var array<int, array{email: string, name: string}>
     */
    protected array $copyRecipients = [];

    /**
     * The blind copy recipients.
     *
     * @var array<int, array{email: string, name: string}>
     */
    protected array $blindCopyRecipients = [];

    /**
     * The attachments.
     *
     * @var array<int, array{path: string, name: string}>
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
    protected string|null $plainBody = null;

    public function __construct(
        protected string $fromEmail = '',
        protected string $fromName = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFrom(string $email, string $name = ''): static
    {
        $this->fromEmail = $email;
        $this->fromName  = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function addRecipient(string $email, string $name = ''): static
    {
        $this->recipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getReplyToRecipients(): array
    {
        return $this->replyToRecipients;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function addReplyTo(string $email, string $name = ''): static
    {
        $this->replyToRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCopyRecipients(): array
    {
        return $this->copyRecipients;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function addCopyRecipient(string $email, string $name = ''): static
    {
        $this->copyRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getBlindCopyRecipients(): array
    {
        return $this->blindCopyRecipients;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function addBlindCopyRecipient(string $email, string $name = ''): static
    {
        $this->blindCopyRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function addAttachment(string $path, string $name = ''): static
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isHtml(): bool
    {
        return $this->isHtml;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setIsHtml(bool $isHtml = true): static
    {
        $this->isHtml = $isHtml;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPlainBody(): string|null
    {
        return $this->plainBody;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setPlainBody(string|null $plainBody = null): static
    {
        $this->plainBody = $plainBody;

        return $this;
    }
}
