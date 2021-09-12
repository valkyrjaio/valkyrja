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

namespace Valkyrja\Crypt\Managers;

use Valkyrja\Container\Container;
use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Crypt as Contract;
use Valkyrja\Crypt\Driver;
use Valkyrja\Support\Type\Cls;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 */
class Crypt implements Contract
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
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The crypts.
     *
     * @var array
     */
    protected array $crypts;

    /**
     * The default driver.
     *
     * @var string
     */
    protected string $defaultDriver;

    /**
     * The default crypt.
     *
     * @var string
     */
    protected string $default;

    /**
     * Crypt constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->crypts         = $config['crypts'];
        $this->defaultAdapter = $config['adapter'];
        $this->defaultDriver  = $config['driver'];
        $this->default        = $config['default'];
    }

    /**
     * @inheritDoc
     */
    public function useCrypt(string $name = null, string $adapter = null): Driver
    {
        // The crypt to use
        $name ??= $this->default;
        // The config to use
        $config = $this->crypts[$name];
        // The adapter to use
        $adapter ??= $config['adapter'] ?? $this->defaultAdapter;
        // The adapter to use
        $driver = $config['driver'] ?? $this->defaultDriver;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->createDriver($driver, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        return $this->useCrypt()->isValidEncryptedMessage($encrypted);
    }

    /**
     * @inheritDoc
     */
    public function encrypt(string $message, string $key = null): string
    {
        return $this->useCrypt()->encrypt($message, $key);
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $encrypted, string $key = null): string
    {
        return $this->useCrypt()->decrypt($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptArray(array $array, string $key = null): string
    {
        return $this->useCrypt()->encryptArray($array, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptArray(string $encrypted, string $key = null): array
    {
        return $this->useCrypt()->decryptArray($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptObject(object $object, string $key = null): string
    {
        return $this->useCrypt()->encryptObject($object, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptObject(string $encrypted, string $key = null): object
    {
        return $this->useCrypt()->decryptObject($encrypted, $key);
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
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Adapter::class,
            [
                $config,
            ]
        );
    }
}
