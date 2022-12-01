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

use Valkyrja\Filesystem\Enums\Visibility;
use Valkyrja\Support\Manager\Manager;

/**
 * Interface Filesystem.
 *
 * @author Melech Mizrachi
 * @extends Manager<Driver, Factory>
 */
interface Filesystem extends Manager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(string $name = null): Driver;

    /**
     * Determine whether a path exists.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Read a file.
     *
     * @param string $path The path
     *
     * @return string|null The contents or null on failure
     */
    public function read(string $path): ?string;

    /**
     * Write a file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @return bool
     */
    public function write(string $path, string $contents): bool;

    /**
     * Write a file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @return bool
     */
    public function writeStream(string $path, $resource): bool;

    /**
     * Update an existing file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @return bool
     */
    public function update(string $path, string $contents): bool;

    /**
     * Update an existing file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @return bool
     */
    public function updateStream(string $path, $resource): bool;

    /**
     * Write a file or update a file depending on existence.
     *
     * @param string $path     The path
     * @param string $contents The contents
     *
     * @return bool
     */
    public function put(string $path, string $contents): bool;

    /**
     * Write a file or update a file depending on existence with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     *
     * @return bool
     */
    public function putStream(string $path, $resource): bool;

    /**
     * Rename a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     *
     * @return bool
     */
    public function rename(string $path, string $newPath): bool;

    /**
     * Copy a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     *
     * @return bool
     */
    public function copy(string $path, string $newPath): bool;

    /**
     * Delete a file.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * Get a file's meta data.
     *
     * @param string $path The path
     *
     * @return array|null An array of meta data or null on failure
     */
    public function metadata(string $path): ?array;

    /**
     * Get a file's mime type.
     *
     * @param string $path The path
     *
     * @return string|null The mime type or null on failure
     */
    public function mimetype(string $path): ?string;

    /**
     * Get a file's size.
     *
     * @param string $path The path
     *
     * @return int|null The size in bytes or null on failure
     */
    public function size(string $path): ?int;

    /**
     * Get a file's timestamp.
     *
     * @param string $path The path
     *
     * @return int|null The timestamp or null on failure
     */
    public function timestamp(string $path): ?int;

    /**
     * Get a file's visibility.
     *
     * @param string $path The path
     *
     * @return string|null The visibility ('public' or 'private') or null on
     *                     failure
     */
    public function visibility(string $path): ?string;

    /**
     * Set a file's visibility.
     *
     * @param string     $path       The path
     * @param Visibility $visibility The visibility
     *
     * @return bool
     */
    public function setVisibility(string $path, Visibility $visibility): bool;

    /**
     * Set a file's visibility to public.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function setVisibilityPublic(string $path): bool;

    /**
     * Set a file's visibility to private.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function setVisibilityPrivate(string $path): bool;

    /**
     * Create a new directory.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function createDir(string $path): bool;

    /**
     * Delete a directory.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function deleteDir(string $path): bool;

    /**
     * List the contents of a directory.
     *
     * @param string|null $directory [optional] The directory
     * @param bool        $recursive [optional] Whether to recurse through the directory
     *
     * @return array
     */
    public function listContents(string $directory = null, bool $recursive = false): array;
}
