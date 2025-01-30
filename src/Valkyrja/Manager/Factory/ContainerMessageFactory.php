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

namespace Valkyrja\Manager\Factory;

use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Factory\Contract\MessageFactory;
use Valkyrja\Manager\Message\Contract\Message;

/**
 * Class ContainerMessageFactory.
 *
 * @author   Melech Mizrachi
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 * @template Message of Message
 *
 * @implements MessageFactory<Adapter, Driver, Message>
 *
 * @extends ContainerFactory<Adapter, Driver>
 */
class ContainerMessageFactory extends ContainerFactory implements MessageFactory
{
    /**
     * @inheritDoc
     *
     * @param class-string<Message> $name The message
     *
     * @return Message
     */
    public function createMessage(string $name, array $config, array $data = []): Message
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
