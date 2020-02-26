<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Filesystem\Filesystems;

use League\Flysystem\AdapterInterface;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Filesystem\Adapter;
use Valkyrja\Filesystem\Enums\Visibility;
use Valkyrja\Filesystem\Filesystem as FilesystemContract;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Filesystem.
 *
 * @author Melech Mizrachi
 */
class Filesystem implements FilesystemContract
{
    use Provides;

    /**
     * The adapters.
     *
     * @var AdapterInterface[]
     */
    protected static array $adapters = [];

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * FlyFilesystem constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app    = $application;
        $this->config = $this->app->config()[CKP::FILESYSTEM];
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            FilesystemContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(
            FilesystemContract::class,
            new static($app)
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
        return $this->getAdapter()->exists($path);
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
        return $this->getAdapter()->read($path);
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
        return $this->getAdapter()->write($path, $contents);
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
        return $this->getAdapter()->writeStream($path, $resource);
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
        return $this->getAdapter()->update($path, $contents);
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
        return $this->getAdapter()->updateStream($path, $resource);
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
        return $this->getAdapter()->put($path, $contents);
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
        return $this->getAdapter()->putStream($path, $resource);
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
        return $this->getAdapter()->rename($path, $newPath);
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
        return $this->getAdapter()->copy($path, $newPath);
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
        return $this->getAdapter()->delete($path);
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
        return $this->getAdapter()->metadata($path);
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
        return $this->getAdapter()->mimetype($path);
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
        return $this->getAdapter()->size($path);
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
        return $this->getAdapter()->timestamp($path);
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
        return $this->getAdapter()->visibility($path);
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
        return $this->getAdapter()->setVisibility($path, $visibility);
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
        return $this->getAdapter()->setVisibilityPublic($path);
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
        return $this->getAdapter()->setVisibilityPrivate($path);
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
        return $this->getAdapter()->createDir($path);
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
        return $this->getAdapter()->deleteDir($path);
    }

    /**
     * List the contents of a directory.
     *
     * @param string $directory [optional] The directory
     * @param bool   $recursive [optional] Whether to recurse through the directory
     *
     * @return array
     */
    public function listContents(string $directory = null, bool $recursive = false): array
    {
        return $this->getAdapter()->listContents($directory, $recursive);
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
        $name ??= $this->config[CKP::CONNECTIONS][$this->config[CKP::DEFAULT]][CKP::ADAPTER];

        if (isset(self::$adapters[$name])) {
            return self::$adapters[$name];
        }

        /** @var Adapter $adapter */
        $adapter = $this->config[CKP::ADAPTERS][$name];

        return self::$adapters[$name] = $adapter::make();
    }

    /**
     * Get the local filesystem.
     *
     * @return Adapter
     */
    public function local(): Adapter
    {
        return $this->getAdapter(CKP::LOCAL);
    }

    /**
     * Get the s3 filesystem.
     *
     * @return Adapter
     */
    public function s3(): Adapter
    {
        return $this->getAdapter(CKP::S3);
    }
}
