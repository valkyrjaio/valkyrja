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

namespace Valkyrja\Support\Manager\Factories;

use Valkyrja\Support\Manager\Adapter;
use Valkyrja\Support\Manager\Driver;
use Valkyrja\Support\Manager\Message;
use Valkyrja\Support\Manager\MessageFactory;
use Valkyrja\Support\Type\Cls;

/**
 * Class ContainerMessageFactory.
 *
 * @author   Melech Mizrachi
 * @template Driver
 * @template Factory
 * @template Message
 * @implements MessageFactory<Adapter, Driver, Message>
 */
class ContainerMessageFactory extends ContainerFactory implements MessageFactory
{
    /**
     * The default driver class.
     *
     * @var string
     */
    protected static string $defaultMessageClass;

    /**
     * @inheritDoc
     */
    public function createMessage(string $name, array $config, array $data = []): Message
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            $this->getMessageDefaultClass($name),
            [
                $config,
                $data,
            ]
        );
    }

    /**
     * Get the default driver class.
     *
     * @param string $name The driver
     *
     * @return string
     */
    protected function getDriverDefaultClass(string $name): string
    {
        return static::$defaultDriverClass;
    }

    /**
     * Get the default message class.
     *
     * @param string $name The message
     *
     * @return string
     */
    protected function getMessageDefaultClass(string $name): string
    {
        return static::$defaultMessageClass;
    }
}
