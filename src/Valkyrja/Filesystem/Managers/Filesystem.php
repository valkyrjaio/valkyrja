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
use Valkyrja\Filesystem\Driver;
use Valkyrja\Filesystem\Enums\Visibility;
use Valkyrja\Filesystem\Filesystem as Contract;

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
    protected static array $driversCache = [];

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
     * The adapters.
     *
     * @var array
     */
    protected array $adapters;

    /**
     * The disks.
     *
     * @var array
     */
    protected array $disks;

    /**
     * The drivers config.
     *
     * @var array
     */
    protected array $drivers;

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
        $this->container   = $container;
        $this->config      = $config;
        $this->disks       = $config['disks'];
        $this->adapters    = $config['adapters'];
        $this->drivers     = $config['drivers'];
        $this->defaultDisk = $config['default'];
    }

    /**
     * Use a disk by name.
     *
     * @param string|null $name    The disk name
     * @param string|null $adapter The adapter
     *
     * @return Driver
     */
    public function useDisk(string $name = null, string $adapter = null): Driver
    {
        // The disk to use
        $name ??= $this->defaultDisk;
        // The disk config to use
        $disk = $this->disks[$name];
        // The adapter to use
        $adapter ??= $disk['adapter'];
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->container->get(
                $this->drivers[$disk['driver']],
                [
                    $disk,
                    $this->adapters[$adapter],
                ]
            );
    }

    /**
     * Determine whether a path exists.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->useDisk()->exists($path);
    }

    /**
     * Read a file.
     *
     * @param string $path The path
     *
     * @return string|null The contents or null on failure
     */
    public function read(string $path): ?string
    {
        return $this->useDisk()->read($path);
    }

    /**
     * Write a file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @return bool
     */
    public function write(string $path, string $contents): bool
    {
        return $this->useDisk()->write($path, $contents);
    }

    /**
     * Write a file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @return bool
     */
    public function writeStream(string $path, $resource): bool
    {
        return $this->useDisk()->writeStream($path, $resource);
    }

    /**
     * Update an existing file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @return bool
     */
    public function update(string $path, string $contents): bool
    {
        return $this->useDisk()->update($path, $contents);
    }

    /**
     * Update an existing file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @return bool
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->useDisk()->updateStream($path, $resource);
    }

    /**
     * Write a file or update a file depending on existence.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @return bool
     */
    public function put(string $path, string $contents): bool
    {
        return $this->useDisk()->put($path, $contents);
    }

    /**
     * Write a file or update a file depending on existence with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @return bool
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->useDisk()->putStream($path, $resource);
    }

    /**
     * Rename a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     *
     * @return bool
     */
    public function rename(string $path, string $newPath): bool
    {
        return $this->useDisk()->rename($path, $newPath);
    }

    /**
     * Copy a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     *
     * @return bool
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->useDisk()->copy($path, $newPath);
    }

    /**
     * Delete a file.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function delete(string $path): bool
    {
        return $this->useDisk()->delete($path);
    }

    /**
     * Get a file's meta data.
     *
     * @param string $path The path
     *
     * @return array|null An array of meta data or null on failure
     */
    public function metadata(string $path): ?array
    {
        return $this->useDisk()->metadata($path);
    }

    /**
     * Get a file's mime type.
     *
     * @param string $path The path
     *
     * @return string|null The mime type or null on failure
     */
    public function mimetype(string $path): ?string
    {
        return $this->useDisk()->mimetype($path);
    }

    /**
     * Get a file's size.
     *
     * @param string $path The path
     *
     * @return int|null The size in bytes or null on failure
     */
    public function size(string $path): ?int
    {
        return $this->useDisk()->size($path);
    }

    /**
     * Get a file's timestamp.
     *
     * @param string $path The path
     *
     * @return int|null The timestamp or null on failure
     */
    public function timestamp(string $path): ?int
    {
        return $this->useDisk()->timestamp($path);
    }

    /**
     * Get a file's visibility.
     *
     * @param string $path The path
     *
     * @return string|null The visibility ('public' or 'private') or null on failure
     */
    public function visibility(string $path): ?string
    {
        return $this->useDisk()->visibility($path);
    }

    /**
     * Set a file's visibility.
     *
     * @param string     $path       The path
     * @param Visibility $visibility The visibility
     *
     * @return bool
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return $this->useDisk()->setVisibility($path, $visibility);
    }

    /**
     * Set a file's visibility to public.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->useDisk()->setVisibilityPublic($path);
    }

    /**
     * Set a file's visibility to private.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->useDisk()->setVisibilityPrivate($path);
    }

    /**
     * Create a new directory.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function createDir(string $path): bool
    {
        return $this->useDisk()->createDir($path);
    }

    /**
     * Delete a directory.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function deleteDir(string $path): bool
    {
        return $this->useDisk()->deleteDir($path);
    }

    /**
     * List the contents of a directory.
     *
     * @param string|null $directory [optional] The directory
     * @param bool        $recursive [optional] Whether to recurse through the directory
     *
     * @return array
     */
    public function listContents(string $directory = null, bool $recursive = false): array
    {
        return $this->useDisk()->listContents($directory, $recursive);
    }
}
