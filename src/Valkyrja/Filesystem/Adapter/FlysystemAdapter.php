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

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator as FlysystemInterface;
use Valkyrja\Filesystem\Adapter\Contract\FlysystemAdapter as Contract;
use Valkyrja\Filesystem\Enum\Visibility;

/**
 * Class FlysystemAdapter.
 *
 * @author Melech Mizrachi
 */
class FlysystemAdapter implements Contract
{
    /**
     * FlysystemAdapter constructor.
     *
     * @param FlysystemInterface $flysystem The Flysystem filesystem
     */
    public function __construct(
        protected FlysystemInterface $flysystem
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function exists(string $path): bool
    {
        return $this->flysystem->has($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function read(string $path): string
    {
        return $this->flysystem->read($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
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
    public function update(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function put(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
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
    public function delete(string $path): bool
    {
        $this->flysystem->delete($path);

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
     *
     * @throws FilesystemException
     */
    public function mimetype(string $path): string|null
    {
        return $this->flysystem->mimeType($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function size(string $path): int|null
    {
        return $this->flysystem->fileSize($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function timestamp(string $path): int|null
    {
        return $this->flysystem->lastModified($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function visibility(string $path): string|null
    {
        return $this->flysystem->visibility($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
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
    public function deleteDir(string $path): bool
    {
        $this->flysystem->deleteDirectory($path);

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws FilesystemException
     */
    public function listContents(string|null $directory = null, bool $recursive = false): array
    {
        return $this->flysystem->listContents($directory ?? '', $recursive)->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getFlysystem(): FlysystemInterface
    {
        return $this->flysystem;
    }
}
