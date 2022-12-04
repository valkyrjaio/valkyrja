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

namespace Valkyrja\Sms\Factories;

use Valkyrja\Manager\Factories\ContainerMessageFactory as Factory;
use Valkyrja\Sms\Adapter;
use Valkyrja\Sms\Driver;
use Valkyrja\Sms\Factory as Contract;
use Valkyrja\Sms\LogAdapter;
use Valkyrja\Sms\Message;
use Valkyrja\Sms\NexmoAdapter;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
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
     */
    protected static string $defaultMessageClass = Message::class;

    /**
     * @inheritDoc
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        return parent::createDriver($name, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        return parent::createAdapter($name, $config);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name, array $config, array $data = []): Message
    {
        return parent::createMessage($name, $config, $data);
    }

    /**
     * @inheritDoc
     */
    protected function getAdapterDefaultClass(string $name): string
    {
        $defaultClass = parent::getAdapterDefaultClass($name);

        if (is_a($name, NexmoAdapter::class, true)) {
            $defaultClass = NexmoAdapter::class;
        } elseif (is_a($name, LogAdapter::class, true)) {
            $defaultClass = LogAdapter::class;
        }

        return $defaultClass;
    }
}
