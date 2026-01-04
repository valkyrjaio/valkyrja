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

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;
use Override;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;

use function array_map;

class FlysystemFilesystem implements FilesystemContract
{
    public function __construct(
        protected FilesystemOperator $flysystem
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function exists(string $path): bool
    {
        return $this->flysystem->has($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function read(string $path): string
    {
        return $this->flysystem->read($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function write(string $path, string $contents): bool
    {
        $this->flysystem->write($path, $contents);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function writeStream(string $path, $resource): bool
    {
        $this->flysystem->writeStream($path, $resource);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function update(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function updateStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function put(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function putStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function rename(string $path, string $newPath): bool
    {
        $this->flysystem->move($path, $newPath);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function copy(string $path, string $newPath): bool
    {
        $this->flysystem->copy($path, $newPath);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function delete(string $path): bool
    {
        $this->flysystem->delete($path);

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function metadata(string $path): array|null
    {
        return null;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function mimetype(string $path): string|null
    {
        return $this->flysystem->mimeType($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function size(string $path): int|null
    {
        return $this->flysystem->fileSize($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function timestamp(string $path): int|null
    {
        return $this->flysystem->lastModified($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function visibility(string $path): string|null
    {
        return $this->flysystem->visibility($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        $this->flysystem->setVisibility($path, $visibility->value);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function setVisibilityPublic(string $path): bool
    {
        $this->flysystem->setVisibility($path, Visibility::PUBLIC->value);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function setVisibilityPrivate(string $path): bool
    {
        $this->flysystem->setVisibility($path, Visibility::PRIVATE->value);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function createDir(string $path): bool
    {
        $this->flysystem->createDirectory($path);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    #[Override]
    public function deleteDir(string $path): bool
    {
        $this->flysystem->deleteDirectory($path);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    #[Override]
    public function listContents(string|null $directory = null, bool $recursive = false): array
    {
        return array_map(
            /**
             * @return array<string, string|int>
             */
            static fn (StorageAttributes $attributes): array => (array) $attributes->jsonSerialize(),
            $this->flysystem->listContents($directory ?? '', $recursive)->toArray()
        );
    }

    /**
     * @inheritDoc
     */
    public function getFlysystem(): FilesystemOperator
    {
        return $this->flysystem;
    }
}
