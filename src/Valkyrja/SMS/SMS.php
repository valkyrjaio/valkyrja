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

namespace Valkyrja\SMS;

/**
 * Interface SMS.
 *
 * @author Melech Mizrachi
 */
interface SMS
{
    /**
     * Use a messenger by name.
     *
     * @param string|null $name    [optional] The messenger name
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
     */
    public function useMessenger(string $name = null, string $adapter = null): Driver;

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
