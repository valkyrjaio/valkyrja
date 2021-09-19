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

namespace Valkyrja\Mail\Managers;

use Valkyrja\Container\Container;
use Valkyrja\Mail\Adapter;
use Valkyrja\Mail\Driver;
use Valkyrja\Mail\LogAdapter;
use Valkyrja\Mail\Mail as Contract;
use Valkyrja\Mail\MailgunAdapter;
use Valkyrja\Mail\Message;
use Valkyrja\Mail\PHPMailerAdapter;
use Valkyrja\Support\Type\Cls;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 */
class Mail implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

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
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The default driver.
     *
     * @var string
     */
    protected string $defaultDriver;

    /**
     * The mailers.
     *
     * @var array[]
     */
    protected array $mailers;

    /**
     * The default message class.
     *
     * @var string
     */
    protected string $defaultMessageClass;

    /**
     * The messages config.
     *
     * @var array[]
     */
    protected array $messages;

    /**
     * The default mailer.
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
        $this->container           = $container;
        $this->config              = $config;
        $this->default             = $config['default'];
        $this->defaultMessage      = $config['defaultMessage'];
        $this->defaultAdapter      = $config['adapter'];
        $this->defaultDriver       = $config['driver'];
        $this->defaultMessageClass = $config['message'];
        $this->mailers             = $config['mailers'];
        $this->messages            = $config['messages'];
    }

    /**
     * @inheritDoc
     */
    public function useMailer(string $name = null, string $adapter = null): Driver
    {
        // The mailer to use
        $name ??= $this->default;
        // The config to use
        $config = $this->mailers[$name];
        // The driver to use
        $driver ??= $config['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter ??= $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->createDriver($driver, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name = null, array $data = []): Message
    {
        // The name of the message to use
        $name ??= $this->defaultMessage;
        // The message config
        $config = $this->messages[$name];
        // The message to use
        $message = $config['message'] ?? $this->defaultMessageClass;

        return Cls::getDefaultableService(
            $this->container,
            $message,
            Message::class,
            [
                $config,
                $data,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->useMailer()->send($message);
    }

    /**
     * Get an driver by name.
     *
     * @param string $name    The driver
     * @param string $adapter The adapter
     * @param array  $config  The config
     *
     * @return Driver
     */
    protected function createDriver(string $name, string $adapter, array $config): Driver
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Driver::class,
            [
                $this->createAdapter($adapter, $config),
            ]
        );
    }

    /**
     * Get an adapter by name.
     *
     * @param string $name   The adapter
     * @param array  $config The config
     *
     * @return Adapter
     */
    protected function createAdapter(string $name, array $config): Adapter
    {
        $defaultClass = Adapter::class;

        if (Cls::inherits($name, MailgunAdapter::class)) {
            $defaultClass = MailgunAdapter::class;
        } elseif (Cls::inherits($name, PHPMailerAdapter::class)) {
            $defaultClass = PHPMailerAdapter::class;
        } elseif (Cls::inherits($name, LogAdapter::class)) {
            $defaultClass = LogAdapter::class;
        }

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultClass,
            [
                $config,
            ]
        );
    }
}
