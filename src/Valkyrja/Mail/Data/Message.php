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
     * @var array<int, array{email: non-empty-string, name: string}>
     */
    protected array $recipients = [];

    /**
     * The reply to recipients.
     *
     * @var array<int, array{email: non-empty-string, name: string}>
     */
    protected array $replyToRecipients = [];

    /**
     * The copy recipients.
     *
     * @var array<int, array{email: non-empty-string, name: string}>
     */
    protected array $copyRecipients = [];

    /**
     * The blind copy recipients.
     *
     * @var array<int, array{email: non-empty-string, name: string}>
     */
    protected array $blindCopyRecipients = [];

    /**
     * The attachments.
     *
     * @var array<int, array{path: non-empty-string, name: string}>
     */
    protected array $attachments = [];

    /**
     * Whether the message body is html.
     *
     * @var bool
     */
    protected bool $isHtml = false;

    /**
     * The plain body.
     *
     * @var non-empty-string|null
     */
    protected string|null $plainBody = null;

    /**
     * @param non-empty-string $fromEmail
     * @param string           $fromName
     * @param non-empty-string $subject
     * @param non-empty-string $body
     */
    public function __construct(
        protected string $fromEmail,
        protected string $fromName,
        protected string $subject,
        protected string $body
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
    public function withFrom(string $email, string $name = ''): static
    {
        $new = clone $this;

        $new->fromEmail = $email;
        $new->fromName  = $name;

        return $new;
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
    public function withAddedRecipient(string $email, string $name = ''): static
    {
        $new = clone $this;

        $new->recipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $new;
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
    public function withAddedReplyToRecipient(string $email, string $name = ''): static
    {
        $new = clone $this;

        $new->replyToRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $new;
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
    public function withAddedCopyRecipient(string $email, string $name = ''): static
    {
        $new = clone $this;

        $new->copyRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $new;
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
    public function withAddedBlindCopyRecipient(string $email, string $name = ''): static
    {
        $new = clone $this;

        $new->blindCopyRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $new;
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
    public function withAddedAttachment(string $path, string $name = ''): static
    {
        $new = clone $this;

        $new->attachments[] = [
            'path' => $path,
            'name' => $name,
        ];

        return $new;
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
    public function withSubject(string $subject): static
    {
        $new = clone $this;

        $new->subject = $subject;

        return $new;
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
    public function withBody(string $body): static
    {
        $new = clone $this;

        $new->body = $body;

        return $new;
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
    public function withIsHtml(bool $isHtml = true): static
    {
        $new = clone $this;

        $new->isHtml = $isHtml;

        return $new;
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
    public function withPlainBody(string|null $plainBody = null): static
    {
        $new = clone $this;

        $new->plainBody = $plainBody;

        return $new;
    }
}
