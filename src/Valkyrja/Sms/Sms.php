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
        // The config to use
        $config = $this->config->configurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid configuration");
        // The driver to use
        $driverClass = $config->driverClass;
        // The adapter to use
        $adapterClass = $config->adapterClass;
        // The cache key to use
        $cacheKey = $name . $adapterClass;

        return $this->drivers[$cacheKey]
            ?? $this->factory->createDriver($driverClass, $adapterClass, $config);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string|null $name = null, array $data = []): Message
    {
        // The name of the message to use
        $name ??= $this->config->defaultMessageConfiguration;
        // The message config
        /** @var MessageConfiguration $config */
        $config = $this->config->messageConfigurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid message");
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
}
