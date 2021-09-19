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

namespace Valkyrja\Filesystem\Managers;

use Valkyrja\Container\Container;
use Valkyrja\Filesystem\Adapter;
use Valkyrja\Filesystem\Driver;
use Valkyrja\Filesystem\Enums\Visibility;
use Valkyrja\Filesystem\Filesystem as Contract;
use Valkyrja\Filesystem\FlysystemAdapter;
use Valkyrja\Support\Type\Cls;

/**
 * Class Filesystem.
 *
 * @author Melech Mizrachi
 */
class Filesystem implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

    /**
     * The container service.
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
     * The disks.
     *
     * @var array
     */
    protected array $disks;

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
     * The default disk.
     *
     * @var string
     */
    protected string $defaultDisk;

    /**
     * Filesystem constructor.
     *
     * @param Container $container The container service
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->disks          = $config['disks'];
        $this->defaultAdapter = $config['adapter'];
        $this->defaultDriver  = $config['driver'];
        $this->defaultDisk    = $config['default'];
    }

    /**
     * @inheritDoc
     */
    public function useDisk(string $name = null, string $adapter = null): Driver
    {
        // The disk to use
        $name ??= $this->defaultDisk;
        // The disk config to use
        $disk = $this->disks[$name];
        // The driver to use
        $driver ??= $disk['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter ??= $disk['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->createDriver($driver, $adapter, $disk);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return $this->useDisk()->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function read(string $path): ?string
    {
        return $this->useDisk()->read($path);
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents): bool
    {
        return $this->useDisk()->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function writeStream(string $path, $resource): bool
    {
        return $this->useDisk()->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function update(string $path, string $contents): bool
    {
        return $this->useDisk()->update($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->useDisk()->updateStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, string $contents): bool
    {
        return $this->useDisk()->put($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->useDisk()->putStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function rename(string $path, string $newPath): bool
    {
        return $this->useDisk()->rename($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->useDisk()->copy($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): bool
    {
        return $this->useDisk()->delete($path);
    }

    /**
     * @inheritDoc
     */
    public function metadata(string $path): ?array
    {
        return $this->useDisk()->metadata($path);
    }

    /**
     * @inheritDoc
     */
    public function mimetype(string $path): ?string
    {
        return $this->useDisk()->mimetype($path);
    }

    /**
     * @inheritDoc
     */
    public function size(string $path): ?int
    {
        return $this->useDisk()->size($path);
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $path): ?int
    {
        return $this->useDisk()->timestamp($path);
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): ?string
    {
        return $this->useDisk()->visibility($path);
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return $this->useDisk()->setVisibility($path, $visibility);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->useDisk()->setVisibilityPublic($path);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->useDisk()->setVisibilityPrivate($path);
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $path): bool
    {
        return $this->useDisk()->createDir($path);
    }

    /**
     * @inheritDoc
     */
    public function deleteDir(string $path): bool
    {
        return $this->useDisk()->deleteDir($path);
    }

    /**
     * @inheritDoc
     */
    public function listContents(string $directory = null, bool $recursive = false): array
    {
        return $this->useDisk()->listContents($directory, $recursive);
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

        if (Cls::inherits($name, FlysystemAdapter::class)) {
            $defaultClass = FlysystemAdapter::class;
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
