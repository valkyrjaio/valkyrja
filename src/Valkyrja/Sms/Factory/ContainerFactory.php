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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Config\Configuration;
use Valkyrja\Sms\Config\MessageConfiguration;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Factory\Contract\Factory as Contract;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 */
class ContainerFactory implements Contract
{
    /**
     * ContainerFactory constructor.
     */
    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * @inheritDoc
     *
     * @template Driver of Driver
     *
     * @param class-string<Driver>  $name    The driver
     * @param class-string<Adapter> $adapter The adapter
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, Configuration $config): Driver
    {
        return $this->container->get(
            $name,
            [
                $this->createAdapter($adapter, $config),
            ]
        );
    }

    /**
     * @inheritDoc
     *
     * @template Adapter of Adapter
     *
     * @param class-string<Adapter> $name The adapter
     *
     * @return Adapter
     */
    public function createAdapter(string $name, Configuration $config): Adapter
    {
        return $this->container->get(
            $name,
            [
                $config,
            ]
        );
    }

    /**
     * @inheritDoc
     *
     * @template Message of Message
     *
     * @param class-string<Message> $name The message
     *
     * @return Message
     */
    public function createMessage(string $name, MessageConfiguration $config, array $data = []): Message
    {
        return $this->container->get(
            $name,
            [
                $config,
                $data,
            ]
        );
    }
}
