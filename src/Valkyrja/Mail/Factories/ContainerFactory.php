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

namespace Valkyrja\Mail\Factories;

use Valkyrja\Mail\Adapter;
use Valkyrja\Mail\Driver;
use Valkyrja\Mail\Factory as Contract;
use Valkyrja\Mail\LogAdapter;
use Valkyrja\Mail\MailgunAdapter;
use Valkyrja\Mail\Message;
use Valkyrja\Mail\PHPMailerAdapter;
use Valkyrja\Support\Manager\Factories\ContainerFactoryWithMessage as Factory;
use Valkyrja\Support\Type\Cls;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 * @extends Factory<Driver, Adapter, Message>
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

        if (Cls::inherits($name, MailgunAdapter::class)) {
            $defaultClass = MailgunAdapter::class;
        } elseif (Cls::inherits($name, PHPMailerAdapter::class)) {
            $defaultClass = PHPMailerAdapter::class;
        } elseif (Cls::inherits($name, LogAdapter::class)) {
            $defaultClass = LogAdapter::class;
        }

        return $defaultClass;
    }
}
