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

namespace Valkyrja\Broadcast\Managers;

use Valkyrja\Broadcast\Adapter;
use Valkyrja\Broadcast\Broadcast as Contract;
use Valkyrja\Broadcast\Message;
use Valkyrja\Container\Container;

/**
 * Class Broadcaster.
 *
 * @author Melech Mizrachi
 */
class Broadcast implements Contract
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
     * Broadcaster constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->defaultAdapter = $config['adapter'];
        $this->defaultMessage = $config['message'];
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name = null, array $data = []): Message
    {
        return $this->container->get(
            $this->config['messages'][$name ?? $this->defaultMessage] ?? $name,
            $data
        );
    }

    /**
     * @inheritDoc
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
