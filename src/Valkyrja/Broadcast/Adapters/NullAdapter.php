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

namespace Valkyrja\Broadcast\Adapters;

use InvalidArgumentException;
use JsonException;
use Valkyrja\Broadcast\Adapter as Contract;
use Valkyrja\Broadcast\Message;

use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
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
     * @throws JsonException If a malformed message is provided
     *
     * @return bool
     */
    public function determineKeyValueMatch(string $key, $value, string $message): bool
    {
        $decodedMessage = json_decode($message, true, 512, JSON_THROW_ON_ERROR);

        return isset($decodedMessage[$key]) && $decodedMessage[$key] === $value;
    }

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void
    {
    }
}
