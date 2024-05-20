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

use Valkyrja\Broadcast\Adapter as Contract;
use Valkyrja\Broadcast\Message;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
{
    /**
     * @inheritDoc
     */
    public function determineKeyValueMatch(string $key, $value, string $message): bool
    {
        $decodedMessage = Arr::fromString($message);

        return isset($decodedMessage[$key]) && $decodedMessage[$key] === $value;
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
    }
}
