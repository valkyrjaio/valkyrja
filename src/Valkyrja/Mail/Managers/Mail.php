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
use Valkyrja\Mail\Mail as Contract;
use Valkyrja\Mail\Message;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 */
class Mail implements Contract
{
    /**
     * The adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

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
     * The default message.
     *
     * @var string
     */
    protected string $defaultMessage;

    /**
     * SMS constructor.
     *
     * @param Container $container
     * @param array     $config The SMS config
     */
    public function __construct(Container $container, array $config)
    {
        $this->config         = $config;
        $this->container      = $container;
        $this->defaultAdapter = $config['adapter'];
        $this->defaultMessage = $config['message'];
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
        return $this->container->get(
            $this->config['messages'][$name ?? $this->defaultMessage] ?? $name,
            $data
        );
    }

    /**
     * Get an adapter by name.
     *
     * @param string|null $name The adapter name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        return self::$adapters[$name]
            ?? self::$adapters[$name] = $this->container->getSingleton(
                $this->config['adapters'][$name]['driver']
            );
    }
}
