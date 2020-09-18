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

namespace Valkyrja\SMS\Managers;

use Valkyrja\Container\Container;
use Valkyrja\SMS\Driver;
use Valkyrja\SMS\Message;
use Valkyrja\SMS\SMS as Contract;

/**
 * Class SMS.
 *
 * @author Melech Mizrachi
 */
class SMS implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $driversCache = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The adapters.
     *
     * @var string[]
     */
    protected array $adapters;

    /**
     * The drivers config.
     *
     * @var string[]
     */
    protected array $drivers;

    /**
     * The messengers.
     *
     * @var array[]
     */
    protected array $messengers;

    /**
     * The message adapters.
     *
     * @var string[]
     */
    protected array $messageAdapters;

    /**
     * The messages config.
     *
     * @var array[]
     */
    protected array $messages;

    /**
     * The default messenger.
     *
     * @var string
     */
    protected string $default;

    /**
     * The default message.
     *
     * @var string
     */
    protected string $defaultMessage;

    /**
     * The key
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Mail constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container       = $container;
        $this->config          = $config;
        $this->adapters        = $config['adapters'];
        $this->drivers         = $config['drivers'];
        $this->messengers      = $config['messengers'];
        $this->default         = $config['default'];
        $this->defaultMessage  = $config['defaultMessage'];
        $this->messageAdapters = $config['messageAdapters'];
        $this->messages        = $config['messages'];
    }

    /**
     * Use a messenger by name.
     *
     * @param string|null $name    [optional] The messenger name
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
     */
    public function useMessenger(string $name = null, string $adapter = null): Driver
    {
        // The messenger to use
        $name ??= $this->default;
        // The config to use
        $config = $this->messengers[$name];
        // The adapter to use
        $adapter ??= $config['adapter'];
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->container->get(
                $this->drivers[$config['driver']],
                [
                    $config,
                    $this->adapters[$adapter],
                ]
            );
    }

    /**
     * Create a new message.
     *
     * @param string|null $name [optional] The name of the message
     * @param array       $data [optional] The data
     *
     * @return Message
     */
    public function createMessage(string $name = null, array $data = []): Message
    {
        // The name of the message to use
        $name ??= $this->defaultMessage;
        // The message config
        $config = $this->messages[$name];
        // The adapter to use
        $adapter = $config['adapter'];

        return $this->container->get(
            $this->messageAdapters[$adapter],
            [
                $config,
                $data,
            ]
        );
    }

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void
    {
        $this->useMessenger()->send($message);
    }
}
