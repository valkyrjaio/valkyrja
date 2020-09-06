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
 * Interface Mail.
 *
 * @author Melech Mizrachi
 */
interface Mail
{
    /**
     * Use a mailer by name.
     *
     * @param string|null $name    [optional] The mailer name
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
     */
    public function useMailer(string $name = null, string $adapter = null): Driver;

    /**
     * Create a new message.
     *
     * @param string|null $name [optional] The name of the message
     *
     * @return Message
     */
    public function createMessage(string $name = null): Message;

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void;
}
