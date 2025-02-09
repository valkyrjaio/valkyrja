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

namespace Valkyrja\Broadcast;

use Valkyrja\Broadcast\Adapter\Contract\Adapter;
use Valkyrja\Broadcast\Contract\Broadcast as Contract;
use Valkyrja\Broadcast\Driver\Contract\Driver;
use Valkyrja\Broadcast\Factory\Contract\Factory;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Manager\MessageManager as Manager;

/**
 * Class Broadcast.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Adapter, Driver, Factory, Message>
 *
 * @property Factory $factory
 */
class Broadcast extends Manager implements Contract
{
    /**
     * Broadcast constructor.
     *
     * @param Factory                     $factory The factory
     * @param Config|array<string, mixed> $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['broadcasters'];
    }

    /**
     * @inheritDoc
     */
    public function use(?string $name = null): Driver
    {
        /** @var Driver $driver */
        $driver = parent::use($name);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function createMessage(?string $name = null, array $data = []): Message
    {
        /** @var Message $message */
        $message = parent::createMessage($name, $data);

        return $message;
    }
}
