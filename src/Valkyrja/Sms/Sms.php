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

namespace Valkyrja\Sms;

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Sms\Config\Configuration;
use Valkyrja\Sms\Config\MessageConfiguration;
use Valkyrja\Sms\Contract\Sms as Contract;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Factory\Contract\Factory;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Class Sms.
 *
 * @author Melech Mizrachi
 */
class Sms implements Contract
{
    /**
     * @var Driver[]
     */
    protected array $drivers = [];

    /**
     * Sms constructor.
     */
    public function __construct(
        protected Factory $factory,
        protected Config $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->config->defaultConfiguration;

        return $this->drivers[$name]
            ??= $this->createDriverForName($name);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string|null $name = null, array $data = []): Message
    {
        // The name of the message to use
        $name ??= $this->config->defaultMessageConfiguration;
        // The message config
        $config = $this->config->messageConfigurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid message configuration");

        if (! $config instanceof MessageConfiguration) {
            throw new RuntimeException("$name is an invalid message configuration");
        }

        // The message to use
        $class = $config->messageClass;

        return $this->factory->createMessage($class, $config, $data);
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->use()->send($message);
    }

    /**
     * Create a driver for a given name.
     */
    protected function createDriverForName(string $name): Driver
    {
        // The config to use
        $config = $this->config->configurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid configuration");

        if (! $config instanceof Configuration) {
            throw new RuntimeException("$name is an invalid configuration");
        }

        // The driver to use
        $driverClass = $config->driverClass;
        // The adapter to use
        $adapterClass = $config->adapterClass;

        return $this->factory->createDriver($driverClass, $adapterClass, $config);
    }
}
