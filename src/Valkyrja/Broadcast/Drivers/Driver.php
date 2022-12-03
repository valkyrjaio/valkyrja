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

namespace Valkyrja\Broadcast\Drivers;

use Valkyrja\Broadcast\Adapter;
use Valkyrja\Broadcast\Driver as Contract;
use Valkyrja\Broadcast\Message;
use Valkyrja\Manager\Drivers\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
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
