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

use Valkyrja\Manager\Factory\MessageFactory;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Factory\Contract\Factory as Contract;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 *
 * @extends MessageFactory<Adapter, Driver, Message>
 */
class Factory extends MessageFactory implements Contract
{
    /**
     * @inheritDoc
     *
     * @param class-string<Driver>  $name    The driver
     * @param class-string<Adapter> $adapter The adapter
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        /** @var Driver $driver */
        $driver = parent::createDriver($name, $adapter, $config);

        return $driver;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<Adapter> $name The adapter
     *
     * @return Adapter
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
