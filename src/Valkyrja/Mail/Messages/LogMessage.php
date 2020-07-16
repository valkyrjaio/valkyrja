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

use Valkyrja\Log\Logger;
use Valkyrja\Mail\Message as Contract;

/**
 * Class LogMessage.
 *
 * @author Melech Mizrachi
 */
class LogMessage implements Contract
{
    /**
     * The logger.
     *
     * @var Logger
     */
    protected Logger $logger;

    /**
     * LogMessage constructor.
     *
     * @param Logger $logger The logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Create a new message.
     *
     * @return static
     */
    public function create(): self
    {
        return new static($this->logger);
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
        $this->logger->info(static::class . " addRecipient: ${email}, name ${name}");

        return $this;
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
        $this->logger->info(static::class . " addRecipient: ${email}, name ${name}");

        return $this;
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
        $this->logger->info(static::class . " addReplyTo: ${email}, name ${name}");

        return $this;
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
        $this->logger->info(static::class . " addCopyRecipient: ${email}, name ${name}");

        return $this;
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
        $this->logger->info(static::class . " addBlindCopyRecipient: ${email}, name ${name}");

        return $this;
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
        $this->logger->info(static::class . " addAttachment: ${path}, name ${name}");

        return $this;
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
        $this->logger->info(static::class . " setSubject: ${subject}");

        return $this;
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
        $this->logger->info(static::class . " setBody: ${body}");

        return $this;
    }

    /**
     * Set the html body of the mail.
     *
     * @param string $html The html
     *
     * @return static
     */
    public function setHtml(string $html): self
    {
        $this->logger->info(static::class . " setHtml: ${html}");

        return $this;
    }

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string $plainBody The plain body message
     *
     * @return static
     */
    public function setPlainBody(string $plainBody): self
    {
        $this->logger->info(static::class . " setPlainBody: ${plainBody}");

        return $this;
    }

    /**
     * Send the mail.
     *
     * @return bool
     */
    public function send(): bool
    {
        $this->logger->info(static::class . ' Send');

        return true;
    }
}
