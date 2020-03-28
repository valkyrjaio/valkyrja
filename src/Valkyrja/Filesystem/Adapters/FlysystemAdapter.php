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

namespace Valkyrja\Filesystem;

use InvalidArgumentException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem as FlySystem;
use League\Flysystem\RootViolationException;
use Valkyrja\Filesystem\Enums\Visibility;

/**
 * Abstract Class FlysystemAdapter.
 *
 * @author Melech Mizrachi
 */
abstract class FlysystemAdapter implements Adapter
{
    /**
     * The Fly Filesystem.
     *
     * @var FlySystem
     */
    protected FlySystem $flySystem;

    /**
     * FlysystemAdapter constructor.
     *
     * @param AdapterInterface $adapter The flysystem adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->flySystem = new FlySystem($adapter);
    }

    /**
     * Make a new adapter instance.
     *
     * @return static
     */
    abstract public static function make(): self;

    /**
     * Determine whether a path exists.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->flySystem->has($path);
    }

    /**
     * Read a file.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return string|null The contents or null on failure
     */
    public function read(string $path): ?string
    {
        $read = $this->flySystem->read($path);

        return false !== $read ? $read : null;
    }

    /**
     * Write a file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @throws FileExistsException
     *
     * @return bool
     */
    public function write(string $path, string $contents): bool
    {
        return $this->flySystem->write($path, $contents);
    }

    /**
     * Write a file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @throws FileExistsException
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function writeStream(string $path, $resource): bool
    {
        return $this->flySystem->writeStream($path, $resource);
    }

    /**
     * Update an existing file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @throws FileNotFoundException
     *
     * @return bool
     */
    public function update(string $path, string $contents): bool
    {
        return $this->flySystem->update($path, $contents);
    }

    /**
     * Update an existing file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->flySystem->updateStream($path, $resource);
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
        return $this->flySystem->put($path, $contents);
    }

    /**
     * Write a file or update a file depending on existence with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->flySystem->putStream($path, $resource);
    }

    /**
     * Rename a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     *
     * @throws FileNotFoundException
     * @throws FileExistsException
     *
     * @return bool
     */
    public function rename(string $path, string $newPath): bool
    {
        return $this->flySystem->rename($path, $newPath);
    }

    /**
     * Copy a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     *
     * @throws FileNotFoundException
     * @throws FileExistsException
     *
     * @return bool
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->flySystem->copy($path, $newPath);
    }

    /**
     * Delete a file.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return bool
     */
    public function delete(string $path): bool
    {
        return $this->flySystem->delete($path);
    }

    /**
     * Get a file's meta data.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return array|null An array of meta data or null on failure
     */
    public function metadata(string $path): ?array
    {
        $metadata = $this->flySystem->getMetadata($path);

        return false !== $metadata ? $metadata : null;
    }

    /**
     * Get a file's mime type.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return string|null The mime type or null on failure
     */
    public function mimetype(string $path): ?string
    {
        $mimetype = $this->flySystem->getMimetype($path);

        return false !== $mimetype ? $mimetype : null;
    }

    /**
     * Get a file's size.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return int|null The size in bytes or null on failure
     */
    public function size(string $path): ?int
    {
        $size = $this->flySystem->getSize($path);

        return false !== $size ? $size : null;
    }

    /**
     * Get a file's timestamp.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return int|null The timestamp or null on failure
     */
    public function timestamp(string $path): ?int
    {
        $timestamp = $this->flySystem->getTimestamp($path);

        return false !== $timestamp ? (int) $timestamp : null;
    }

    /**
     * Get a file's visibility.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return string|null The visibility ('public' or 'private') or null on failure
     */
    public function visibility(string $path): ?string
    {
        $visibility = $this->flySystem->getVisibility($path);

        return false !== $visibility ? $visibility : null;
    }

    /**
     * Set a file's visibility.
     *
     * @param string     $path       The path
     * @param Visibility $visibility The visibility
     *
     * @throws FileNotFoundException
     *
     * @return bool
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return $this->flySystem->setVisibility($path, $visibility->getValue());
    }

    /**
     * Set a file's visibility to public.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return bool
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->flySystem->setVisibility($path, Visibility::PUBLIC);
    }

    /**
     * Set a file's visibility to private.
     *
     * @param string $path The path
     *
     * @throws FileNotFoundException
     *
     * @return bool
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->flySystem->setVisibility($path, Visibility::PRIVATE);
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
        return $this->flySystem->createDir($path);
    }

    /**
     * Delete a directory.
     *
     * @param string $path The path
     *
     * @throws RootViolationException
     *
     * @return bool
     */
    public function deleteDir(string $path): bool
    {
        return $this->flySystem->deleteDir($path);
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
        return $this->flySystem->listContents($directory ?? '', $recursive);
    }
}
