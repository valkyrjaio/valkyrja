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

namespace Valkyrja\Mail\Data\Contract;

interface MessageContract
{
    /**
     * Get the from email.
     *
     * @return non-empty-string
     */
    public function getFromEmail(): string;

    /**
     * Get the from name.
     *
     * @return string
     */
    public function getFromName(): string;

    /**
     * Set the mail's sender information.
     *
     * @param non-empty-string $email The email
     * @param string           $name  [optional] The name
     */
    public function withFrom(string $email, string $name = ''): static;

    /**
     * Get the recipients.
     *
     * @return array<int, array{email: non-empty-string, name: string}>
     */
    public function getRecipients(): array;

    /**
     * Add a recipient.
     *
     * @param non-empty-string $email The email
     * @param string           $name  [optional] The name
     */
    public function withAddedRecipient(string $email, string $name = ''): static;

    /**
     * Get the reply to recipients.
     *
     * @return array<int, array{email: non-empty-string, name: string}>
     */
    public function getReplyToRecipients(): array;

    /**
     * Add a Reply-To recipient.
     *
     * @param non-empty-string $email The email
     * @param string           $name  [optional] The name
     */
    public function withAddedReplyToRecipient(string $email, string $name = ''): static;

    /**
     * Get the copy recipients.
     *
     * @return array<int, array{email: non-empty-string, name: string}>
     */
    public function getCopyRecipients(): array;

    /**
     * Add a copy (CC) recipient.
     *
     * @param non-empty-string $email The email
     * @param string           $name  [optional] The name
     */
    public function withAddedCopyRecipient(string $email, string $name = ''): static;

    /**
     * Get the blind copy recipients.
     *
     * @return array<int, array{email: non-empty-string, name: string}>
     */
    public function getBlindCopyRecipients(): array;

    /**
     * Add a blind copy (BCC) recipient.
     *
     * @param non-empty-string $email The email
     * @param string           $name  [optional] The name
     */
    public function withAddedBlindCopyRecipient(string $email, string $name = ''): static;

    /**
     * Get the attachments.
     *
     * @return array<int, array{path: non-empty-string, name: string}>
     */
    public function getAttachments(): array;

    /**
     * Add an attachment from the filesystem.
     *
     * @param non-empty-string $path The path
     * @param string           $name [optional] The name
     */
    public function withAddedAttachment(string $path, string $name = ''): static;

    /**
     * Get the subject.
     *
     * @return non-empty-string
     */
    public function getSubject(): string;

    /**
     * Set the subject.
     *
     * @param non-empty-string $subject The subject
     */
    public function withSubject(string $subject): static;

    /**
     * Get the body.
     *
     * @return non-empty-string
     */
    public function getBody(): string;

    /**
     * Set the body of the mail.
     *
     * @param non-empty-string $body The body
     */
    public function withBody(string $body): static;

    /**
     * Get whether the message body is html.
     */
    public function isHtml(): bool;

    /**
     * Set whether the message body is html.
     *
     * @param bool $isHtml [optional] Whether the message body is html
     */
    public function withIsHtml(bool $isHtml = true): static;

    /**
     * Get the plain body.
     *
     * @return non-empty-string|null
     */
    public function getPlainBody(): string|null;

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param non-empty-string|null $plainBody The plain body
     */
    public function withPlainBody(string|null $plainBody = null): static;
}
