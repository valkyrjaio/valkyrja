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

namespace Valkyrja\Filesystem\Adapters;

use InvalidArgumentException;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface as FlysystemInterface;
use League\Flysystem\RootViolationException;
use Valkyrja\Filesystem\Enums\Visibility;
use Valkyrja\Filesystem\FlysystemAdapter as Contract;

/**
 * Class FlysystemAdapter.
 *
 * @author Melech Mizrachi
 */
class FlysystemAdapter implements Contract
{
    /**
     * The Flysystem filesystem.
     *
     * @var FlysystemInterface
     */
    protected FlysystemInterface $flysystem;

    /**
     * FlysystemAdapter constructor.
     *
     * @param FlysystemInterface $flysystem The Flysystem filesystem
     */
    public function __construct(FlysystemInterface $flysystem)
    {
        $this->flysystem = $flysystem;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return $this->flysystem->has($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function read(string $path): ?string
    {
        $read = $this->flysystem->read($path);

        return false !== $read ? $read : null;
    }

    /**
     * @inheritDoc
     *
     * @throws FileExistsException
     */
    public function write(string $path, string $contents): bool
    {
        return $this->flysystem->write($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws FileExistsException
     * @throws InvalidArgumentException
     */
    public function writeStream(string $path, $resource): bool
    {
        return $this->flysystem->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function update(string $path, string $contents): bool
    {
        return $this->flysystem->update($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->flysystem->updateStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, string $contents): bool
    {
        return $this->flysystem->put($path, $contents);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->flysystem->putStream($path, $resource);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     * @throws FileExistsException
     */
    public function rename(string $path, string $newPath): bool
    {
        return $this->flysystem->rename($path, $newPath);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     * @throws FileExistsException
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->flysystem->copy($path, $newPath);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function delete(string $path): bool
    {
        return $this->flysystem->delete($path);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function metadata(string $path): ?array
    {
        $metadata = $this->flysystem->getMetadata($path);

        return false !== $metadata ? $metadata : null;
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function mimetype(string $path): ?string
    {
        $mimetype = $this->flysystem->getMimetype($path);

        return false !== $mimetype ? $mimetype : null;
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function size(string $path): ?int
    {
        $size = $this->flysystem->getSize($path);

        return false !== $size ? $size : null;
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function timestamp(string $path): ?int
    {
        $timestamp = $this->flysystem->getTimestamp($path);

        return false !== $timestamp ? (int) $timestamp : null;
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function visibility(string $path): ?string
    {
        $visibility = $this->flysystem->getVisibility($path);

        return false !== $visibility ? $visibility : null;
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return $this->flysystem->setVisibility($path, $visibility->getValue());
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->flysystem->setVisibility($path, Visibility::PUBLIC);
    }

    /**
     * @inheritDoc
     *
     * @throws FileNotFoundException
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->flysystem->setVisibility($path, Visibility::PRIVATE);
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $path): bool
    {
        return $this->flysystem->createDir($path);
    }

    /**
     * @inheritDoc
     *
     * @throws RootViolationException
     */
    public function deleteDir(string $path): bool
    {
        return $this->flysystem->deleteDir($path);
    }

    /**
     * @inheritDoc
     */
    public function listContents(string $directory = null, bool $recursive = false): array
    {
        return $this->flysystem->listContents($directory ?? '', $recursive);
    }
}
