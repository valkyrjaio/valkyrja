<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Mail;

/**
 * Interface Mail.
 *
 * @author Melech Mizrachi
 */
interface Mail
{
    /**
     * Set the mail's sender information.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function setFrom(string $address, string $name = ''): bool;

    /**
     * Add a recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addAddress(string $address, string $name = ''): bool;

    /**
     * Add a Reply-To address.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addReplyTo(string $address, string $name = ''): bool;

    /**
     * Add a copy recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addCC(string $address, string $name = ''): bool;

    /**
     * Add a blind copy recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addBCC(string $address, string $name = ''): bool;

    /**
     * Add an attachment from the filesystem.
     *
     * @param string $path
     * @param string $name
     *
     * @return bool
     */
    public function addAttachment(string $path, string $name = ''): bool;

    /**
     * Set whether this is an html message.
     *
     * @param bool $isHTML
     *
     * @return void
     */
    public function isHTML(bool $isHTML = true): void;

    /**
     * Set the subject.
     *
     * @param string $subject
     *
     * @return void
     */
    public function subject(string $subject): void;

    /**
     * Set the body of the mail.
     *
     * @param string $body
     *
     * @return void
     */
    public function body(string $body): void;

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string $body
     *
     * @return void
     */
    public function plainBody(string $body): void;

    /**
     * Send the mail.
     *
     * @return bool
     */
    public function send(): bool;
}
