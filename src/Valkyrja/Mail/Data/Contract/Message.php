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

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message
{
    /**
     * Get the from email.
     *
     * @return string
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
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function setFrom(string $email, string $name = ''): static;

    /**
     * Get the recipients.
     *
     * @return array<int, array{email: string, name: string}>
     */
    public function getRecipients(): array;

    /**
     * Add a recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addRecipient(string $email, string $name = ''): static;

    /**
     * Get the reply to recipients.
     *
     * @return array<int, array{email: string, name: string}>
     */
    public function getReplyToRecipients(): array;

    /**
     * Add a Reply-To recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addReplyTo(string $email, string $name = ''): static;

    /**
     * Get the copy recipients.
     *
     * @return array<int, array{email: string, name: string}>
     */
    public function getCopyRecipients(): array;

    /**
     * Add a copy (CC) recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addCopyRecipient(string $email, string $name = ''): static;

    /**
     * Get the blind copy recipients.
     *
     * @return array<int, array{email: string, name: string}>
     */
    public function getBlindCopyRecipients(): array;

    /**
     * Add a blind copy (BCC) recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addBlindCopyRecipient(string $email, string $name = ''): static;

    /**
     * Get the attachments.
     *
     * @return array<int, array{path: string, name: string}>
     */
    public function getAttachments(): array;

    /**
     * Add an attachment from the filesystem.
     *
     * @param string $path The path
     * @param string $name [optional] The name
     *
     * @return static
     */
    public function addAttachment(string $path, string $name = ''): static;

    /**
     * Get the subject.
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Set the subject.
     *
     * @param string $subject The subject
     *
     * @return static
     */
    public function setSubject(string $subject): static;

    /**
     * Get the body.
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Set the body of the mail.
     *
     * @param string $body The body
     *
     * @return static
     */
    public function setBody(string $body): static;

    /**
     * Get whether the message body is html.
     *
     * @return bool
     */
    public function isHtml(): bool;

    /**
     * Set whether the message body is html.
     *
     * @param bool $isHtml [optional] Whether the message body is html
     *
     * @return static
     */
    public function setIsHtml(bool $isHtml = true): static;

    /**
     * Get the plain body.
     *
     * @return string|null
     */
    public function getPlainBody(): string|null;

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string|null $plainBody The plain body
     *
     * @return static
     */
    public function setPlainBody(string|null $plainBody = null): static;
}
