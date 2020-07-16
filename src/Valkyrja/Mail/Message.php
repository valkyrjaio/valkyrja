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

namespace Valkyrja\Mail;

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message
{
    /**
     * Create a new message.
     *
     * @return static
     */
    public function create(): self;

    /**
     * Set the mail's sender information.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function setFrom(string $email, string $name = ''): self;

    /**
     * Add a recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addRecipient(string $email, string $name = ''): self;

    /**
     * Add a Reply-To recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addReplyTo(string $email, string $name = ''): self;

    /**
     * Add a copy (CC) recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addCopyRecipient(string $email, string $name = ''): self;

    /**
     * Add a blind copy (BCC) recipient.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addBlindCopyRecipient(string $email, string $name = ''): self;

    /**
     * Add an attachment from the filesystem.
     *
     * @param string $path The path
     * @param string $name [optional] The name
     *
     * @return static
     */
    public function addAttachment(string $path, string $name = ''): self;

    /**
     * Set the subject.
     *
     * @param string $subject The subject
     *
     * @return static
     */
    public function setSubject(string $subject): self;

    /**
     * Set the body of the mail.
     *
     * @param string $body The body
     *
     * @return static
     */
    public function setBody(string $body): self;

    /**
     * Set the html body of the mail.
     *
     * @param string $html The html
     *
     * @return static
     */
    public function setHtml(string $html): self;

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string $plainBody The plain body
     *
     * @return static
     */
    public function setPlainBody(string $plainBody): self;

    /**
     * Send the mail.
     *
     * @return bool
     */
    public function send(): bool;
}
