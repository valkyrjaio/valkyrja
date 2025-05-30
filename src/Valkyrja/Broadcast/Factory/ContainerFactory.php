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

namespace Valkyrja\Broadcast\Factory;

use Valkyrja\Broadcast\Adapter\Contract\Adapter;
use Valkyrja\Broadcast\Driver\Contract\Driver;
use Valkyrja\Broadcast\Factory\Contract\Factory as Contract;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Manager\Factory\ContainerMessageFactory as Factory;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 *
 * @extends Factory<Adapter, Driver, Message>
 */
class ContainerFactory extends Factory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        /** @var Driver $driver */
        $driver = parent::createDriver($name, $adapter, $config);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        /** @var Adapter $adapter */
        $adapter = parent::createAdapter($name, $config);

        return $adapter;
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name, array $config, array $data = []): Message
    {
        /** @var Message $message */
        $message = parent::createMessage($name, $config, $data);

        return $message;
    }
}
