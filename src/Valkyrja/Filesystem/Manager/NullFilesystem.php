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

namespace Valkyrja\Filesystem\Manager;

use Override;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;

class NullFilesystem implements FilesystemContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function exists(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function read(string $path): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function write(string $path, string $contents): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function writeStream(string $path, $resource): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(string $path, string $contents): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateStream(string $path, $resource): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function put(string $path, string $contents): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function putStream(string $path, $resource): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rename(string $path, string $newPath): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function copy(string $path, string $newPath): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function delete(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function metadata(string $path): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function mimetype(string $path): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function size(string $path): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function timestamp(string $path): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function visibility(string $path): Visibility
    {
        return Visibility::PUBLIC;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setVisibilityPublic(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setVisibilityPrivate(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createDir(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function deleteDir(string $path): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function listContents(string|null $directory = null, bool $recursive = false): array
    {
        return [];
    }
}
