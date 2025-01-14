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

namespace Valkyrja\Sms\Factory;

use Valkyrja\Manager\Factory\ContainerMessageFactory as Factory;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Adapter\Contract\LogAdapter;
use Valkyrja\Sms\Adapter\Contract\VonageAdapter;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Factory\Contract\Factory as Contract;
use Valkyrja\Sms\Message\Contract\Message;

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
    protected static string $defaultDriverClass = Driver::class;

    /**
     * @inheritDoc
     */
    protected static string $defaultAdapterClass = Adapter::class;

    /**
     * @inheritDoc
     *
     * @var class-string
     */
    protected static string $defaultMessageClass = Message::class;

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

    /**
     * @inheritDoc
     */
    protected function getAdapterDefaultClass(string $name): string
    {
        $defaultClass = parent::getAdapterDefaultClass($name);

        if (is_a($name, VonageAdapter::class, true)) {
            $defaultClass = VonageAdapter::class;
        } elseif (is_a($name, LogAdapter::class, true)) {
            $defaultClass = LogAdapter::class;
        }

        return $defaultClass;
    }
}
