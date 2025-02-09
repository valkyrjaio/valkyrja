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

namespace Valkyrja\Filesystem\Driver;

use Valkyrja\Filesystem\Adapter\Contract\Adapter;
use Valkyrja\Filesystem\Driver\Contract\Driver as Contract;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Manager\Driver\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return $this->adapter->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function read(string $path): string
    {
        return $this->adapter->read($path);
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents): bool
    {
        return $this->adapter->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function writeStream(string $path, $resource): bool
    {
        return $this->adapter->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function update(string $path, string $contents): bool
    {
        return $this->adapter->update($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->adapter->updateStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, string $contents): bool
    {
        return $this->adapter->put($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->adapter->putStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function rename(string $path, string $newPath): bool
    {
        return $this->adapter->rename($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->adapter->copy($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): bool
    {
        return $this->adapter->delete($path);
    }

    /**
     * @inheritDoc
     */
    public function metadata(string $path): ?array
    {
        return $this->adapter->metadata($path);
    }

    /**
     * @inheritDoc
     */
    public function mimetype(string $path): ?string
    {
        return $this->adapter->mimetype($path);
    }

    /**
     * @inheritDoc
     */
    public function size(string $path): ?int
    {
        return $this->adapter->size($path);
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $path): ?int
    {
        return $this->adapter->timestamp($path);
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): ?string
    {
        return $this->adapter->visibility($path);
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return $this->adapter->setVisibility($path, $visibility);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->adapter->setVisibilityPublic($path);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->adapter->setVisibilityPrivate($path);
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $path): bool
    {
        return $this->adapter->createDir($path);
    }

    /**
     * @inheritDoc
     */
    public function deleteDir(string $path): bool
    {
        return $this->adapter->deleteDir($path);
    }

    /**
     * @inheritDoc
     */
    public function listContents(?string $directory = null, bool $recursive = false): array
    {
        return $this->adapter->listContents($directory, $recursive);
    }
}
