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

namespace Valkyrja\Broadcast\Driver;

use Valkyrja\Broadcast\Adapter\Contract\Adapter;
use Valkyrja\Broadcast\Driver\Contract\Driver as Contract;
use Valkyrja\Broadcast\Message\Contract\Message;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Driver constructor.
     */
    public function __construct(
        protected Adapter $adapter
    ) {
    }

    /**
     * @inheritDoc
     */
    public function determineKeyValueMatch(string $key, $value, string $message): bool
    {
        return $this->adapter->determineKeyValueMatch($key, $value, $message);
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->adapter->send($message);
    }
}
