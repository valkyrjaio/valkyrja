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

namespace Valkyrja\Cache\Managers;

use InvalidArgumentException;
use Valkyrja\Cache\Cache as Contract;
use Valkyrja\Cache\Store;
use Valkyrja\Container\Container;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache implements Contract
{
    /**
     * The stores.
     *
     * @var Store[]
     */
    protected static array $stores = [];

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
     * The default store.
     *
     * @var string
     */
    protected string $defaultStore;

    /**
     * Cache constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container    = $container;
        $this->config       = $config;
        $this->defaultStore = $config['default'];
    }

    /**
     * Get a store by name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException If the name doesn't exist
     *
     * @return Store
     */
    public function getStore(string $name = null): Store
    {
        $name ??= $this->defaultStore;

        return self::$stores[$name]
            ?? self::$stores[$name] = $this->container->getSingleton(
                $this->config['stores'][$name]
            );
    }
}
