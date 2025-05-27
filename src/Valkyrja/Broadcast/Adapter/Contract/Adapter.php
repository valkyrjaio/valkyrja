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

namespace Valkyrja\Broadcast\Adapter\Contract;

use InvalidArgumentException;
use JsonException;
use Valkyrja\Broadcast\Message\Contract\Message;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Determine if a key/value pair exists and matches in a broadcast message.
     *  Message is automatically decoded into an array.
     *
     * @param string $key     The key to look for in the message
     * @param mixed  $value   The value to match (string || numeric)
     * @param string $message The message
     *
     * @throws InvalidArgumentException When $value is neither a string or numeric
     * @throws JsonException            If a malformed message is provided
     *
     * @return bool
     */
    public function determineKeyValueMatch(string $key, mixed $value, string $message): bool;

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void;
}
