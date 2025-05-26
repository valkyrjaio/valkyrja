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

namespace Valkyrja\Filesystem\Adapter;

use Valkyrja\Filesystem\Adapter\Contract\Adapter as Contract;
use Valkyrja\Filesystem\Enum\Visibility;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
{
    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function read(string $path): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function writeStream(string $path, $resource): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function update(string $path, string $contents): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function updateStream(string $path, $resource): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, string $contents): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function putStream(string $path, $resource): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function rename(string $path, string $newPath): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function copy(string $path, string $newPath): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function metadata(string $path): array|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function mimetype(string $path): string|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function size(string $path): int|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $path): int|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): string|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPublic(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteDir(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function listContents(?string $directory = null, bool $recursive = false): array
    {
        return [];
    }
}
