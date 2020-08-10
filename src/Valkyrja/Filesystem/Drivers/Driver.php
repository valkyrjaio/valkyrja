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

namespace Valkyrja\Filesystem\Drivers;

use Valkyrja\Filesystem\Adapter;
use Valkyrja\Filesystem\Driver as Contract;
use Valkyrja\Filesystem\Enums\Visibility;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
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
        return $this->adapter->exists($path);
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
        return $this->adapter->read($path);
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
        return $this->adapter->write($path, $contents);
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
        return $this->adapter->writeStream($path, $resource);
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
        return $this->adapter->update($path, $contents);
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
        return $this->adapter->updateStream($path, $resource);
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
        return $this->adapter->put($path, $contents);
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
        return $this->adapter->putStream($path, $resource);
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
        return $this->adapter->rename($path, $newPath);
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
        return $this->adapter->copy($path, $newPath);
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
        return $this->adapter->delete($path);
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
        return $this->adapter->metadata($path);
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
        return $this->adapter->mimetype($path);
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
        return $this->adapter->size($path);
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
        return $this->adapter->timestamp($path);
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
        return $this->adapter->visibility($path);
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
        return $this->adapter->setVisibility($path, $visibility);
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
        return $this->adapter->setVisibilityPublic($path);
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
        return $this->adapter->setVisibilityPrivate($path);
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
        return $this->adapter->createDir($path);
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
        return $this->adapter->deleteDir($path);
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
        return $this->adapter->listContents($directory, $recursive);
    }
}
