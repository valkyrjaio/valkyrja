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

namespace Valkyrja\Filesystem\Manager\Contract;

use Valkyrja\Filesystem\Enum\Visibility;

interface FilesystemContract
{
    /**
     * Determine whether a path exists.
     *
     * @param string $path The path
     */
    public function exists(string $path): bool;

    /**
     * Read a file.
     *
     * @param string $path The path
     *
     * @return string The contents
     */
    public function read(string $path): string;

    /**
     * Write a file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     */
    public function write(string $path, string $contents): bool;

    /**
     * Write a file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     */
    public function writeStream(string $path, $resource): bool;

    /**
     * Update an existing file.
     *
     * @param string $path     The path
     * @param string $contents The contents
     */
    public function update(string $path, string $contents): bool;

    /**
     * Update an existing file with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     */
    public function updateStream(string $path, $resource): bool;

    /**
     * Write a file or update a file depending on existence.
     *
     * @param string $path     The path
     * @param string $contents The contents
     */
    public function put(string $path, string $contents): bool;

    /**
     * Write a file or update a file depending on existence with a stream.
     *
     * @param string   $path     The path
     * @param resource $resource The resource
     */
    public function putStream(string $path, $resource): bool;

    /**
     * Rename a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     */
    public function rename(string $path, string $newPath): bool;

    /**
     * Copy a file.
     *
     * @param string $path    The existing path
     * @param string $newPath The new path
     */
    public function copy(string $path, string $newPath): bool;

    /**
     * Delete a file.
     *
     * @param string $path The path
     */
    public function delete(string $path): bool;

    /**
     * Get a file's meta data.
     *
     * @param string $path The path
     *
     * @return array<string, string|int|null> An array of meta data
     */
    public function metadata(string $path): array;

    /**
     * Get a file's mime type.
     *
     * @param string $path The path
     *
     * @return string The mime type
     */
    public function mimetype(string $path): string;

    /**
     * Get a file's size.
     *
     * @param string $path The path
     *
     * @return int The size in bytes
     */
    public function size(string $path): int;

    /**
     * Get a file's timestamp.
     *
     * @param string $path The path
     *
     * @return int The timestamp
     */
    public function timestamp(string $path): int;

    /**
     * Get a file's visibility.
     *
     * @param string $path The path
     *
     * @return Visibility The visibility ('public' or 'private')
     */
    public function visibility(string $path): Visibility;

    /**
     * Set a file's visibility.
     *
     * @param string     $path       The path
     * @param Visibility $visibility The visibility
     */
    public function setVisibility(string $path, Visibility $visibility): bool;

    /**
     * Set a file's visibility to public.
     *
     * @param string $path The path
     */
    public function setVisibilityPublic(string $path): bool;

    /**
     * Set a file's visibility to private.
     *
     * @param string $path The path
     */
    public function setVisibilityPrivate(string $path): bool;

    /**
     * Create a new directory.
     *
     * @param string $path The path
     */
    public function createDir(string $path): bool;

    /**
     * Delete a directory.
     *
     * @param string $path The path
     */
    public function deleteDir(string $path): bool;

    /**
     * List the contents of a directory.
     *
     * @param string $directory [optional] The directory
     * @param bool   $recursive [optional] Whether to recurse through the directory
     *
     * @return array<string, string|int>[]
     */
    public function listContents(string $directory = '', bool $recursive = false): array;
}
