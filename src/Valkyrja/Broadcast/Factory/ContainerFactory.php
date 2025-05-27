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
use Valkyrja\Broadcast\Config\Configuration;
use Valkyrja\Broadcast\Config\MessageConfiguration;
use Valkyrja\Broadcast\Driver\Contract\Driver;
use Valkyrja\Broadcast\Factory\Contract\Factory as Contract;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Container\Contract\Container;

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
                $this->container,
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
                $this->container,
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
                $this->container,
                $config,
                $data,
            ]
        );
    }
}
